<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use backend\models\Auction;

/**
 * @property int    $id
 * @property int    $auction_id
 * @property int    $user_id
 * @property float  $amount
 * @property string $created_at
 *
 * @property Auction $auction
 * @property User    $user
 */
class Bid extends ActiveRecord
{
    public static function tableName()
    {
        return 'bid';
    }

    public function rules()
    {
        return [
            [['auction_id', 'user_id', 'amount'], 'required'],
            [['auction_id', 'user_id'], 'integer'],
            [['amount'], 'number', 'min' => 0.01],
            [['created_at'], 'safe'],
        ];
    }

    public function getAuction()
    {
        return $this->hasOne(Auction::class, ['id' => 'auction_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Highest bid amount placed on an auction, or null if no bids yet.
     */
    public static function highestAmount(int $auctionId): ?float
    {
        $row = (new \yii\db\Query())
            ->select('MAX(amount) as m')
            ->from('bid')
            ->where(['auction_id' => $auctionId])
            ->one();
        return $row && $row['m'] !== null ? (float)$row['m'] : null;
    }

    /**
     * Snapshot of an auction's bid state — used by both the place-bid response
     * and the initial page load. Includes the floor (start_price), increment,
     * current high, and the N most recent bids with display labels.
     */
    /**
     * Read an Auction attribute without throwing if the column hasn't been
     * added to the DB yet. Lets the bid feature keep working before the
     * `bid_increment` / `current_price` ALTER has been run on the DB.
     */
    public static function safeAttr(Auction $auction, string $attr, $default = null)
    {
        if (!$auction->hasAttribute($attr)) {
            return $default;
        }
        $v = $auction->getAttribute($attr);
        return $v === null ? $default : $v;
    }

    /**
     * Close an auction whose end_date has passed but that no admin finished:
     * record the highest bid as the winner and email them. Runs lazily from
     * snapshot(), i.e. on the first page view / poll after the end time.
     * The conditional UPDATE (closed_at IS NULL) makes it safe when several
     * viewers poll at once — only one request wins and sends the email.
     */
    public static function finalizeIfExpired(Auction $auction): bool
    {
        $endTs = $auction->end_date ? strtotime($auction->end_date) : false;
        if (!$auction->hasAttribute('closed_at') || $auction->closed_at
            || !$endTs || $endTs <= 0 || $endTs > time()) {   // <= 0: '0000-00-00' dates
            return false;
        }
        $top = static::find()
            ->where(['auction_id' => $auction->id])
            ->orderBy(['amount' => SORT_DESC, 'id' => SORT_ASC])
            ->one();

        $set = ['closed_at' => $auction->end_date];
        if ($top) {
            foreach (['winner_user_id' => (int)$top->user_id, 'winning_bid_id' => (int)$top->id,
                      'winning_amount' => $top->amount, 'current_price' => $top->amount] as $col => $val) {
                if ($auction->hasAttribute($col)) {
                    $set[$col] = $val;
                }
            }
        }
        $won = Auction::updateAll($set, ['and', ['id' => $auction->id], ['closed_at' => null]]) === 1;
        $auction->refresh();
        if ($won && $top) {
            \common\components\AuctionNotifier::sendWinnerEmail($auction, $top);
        }
        return $won;
    }

    public static function snapshot(Auction $auction, int $limit = 20): array
    {
        self::finalizeIfExpired($auction);

        $rows = static::find()
            ->with('user.customer')
            ->where(['auction_id' => $auction->id])
            ->orderBy(['id' => SORT_DESC])
            ->limit($limit)
            ->all();

        $bids = [];
        foreach ($rows as $b) {
            $bids[] = [
                'id'         => (int)$b->id,
                'user_id'    => (int)$b->user_id,
                'amount'     => (float)$b->amount,
                'name'       => self::displayName($b->user),
                'created_at' => $b->created_at,
            ];
        }

        $high = $bids ? $bids[0]['amount'] : null;
        $floor = (float) self::safeAttr($auction, 'start_price', 0);

        // Treat an auction as closed if EITHER closed_at is set (admin clicked
        // Close) OR end_date has passed. The latter makes the feature work
        // even before the `closed_at`/`winner_*` columns have been added —
        // the close action still pulls end_date forward to NOW().
        $closedAt = self::safeAttr($auction, 'closed_at');
        $endTs    = $auction->end_date ? strtotime($auction->end_date) : null;
        $isClosed = (bool)$closedAt || ($endTs !== null && $endTs <= time());

        $winner = null;
        if ($isClosed) {
            // Prefer the persisted winner.
            $winUid = (int) self::safeAttr($auction, 'winner_user_id', 0);
            $winAmt = (float) self::safeAttr($auction, 'winning_amount', 0);
            if ($winUid) {
                $u = User::findOne($winUid);
                $winner = [
                    'user_id' => $winUid,
                    'name'    => self::displayName($u),
                    'amount'  => $winAmt ?: ($high ?? 0),
                ];
            } elseif (!empty($bids)) {
                // Fall back to the highest bid (= the latest bid, since each
                // new bid must exceed the previous one).
                $top = $bids[0];
                $winner = [
                    'user_id' => (int)$top['user_id'],
                    'name'    => (string)$top['name'],
                    'amount'  => (float)$top['amount'],
                ];
            }
        }

        return [
            'auction_id'    => (int)$auction->id,
            'start_price'   => $floor,
            'increment'     => (float) self::safeAttr($auction, 'bid_increment', 50),
            'current_price' => $high !== null ? (float)$high : $floor,
            'next_minimum'  => self::nextMinimum($auction, $high),
            'bids'          => $bids,
            'closed'        => $isClosed,
            'closed_at'     => $closedAt,
            'winner'        => $winner,
        ];
    }

    public static function nextMinimum(Auction $auction, ?float $currentHigh): float
    {
        $floor = (float) self::safeAttr($auction, 'start_price', 0);
        $incr  = (float) self::safeAttr($auction, 'bid_increment', 50);
        if ($currentHigh === null) {
            return $floor > 0 ? $floor : $incr;
        }
        return round($currentHigh + $incr, 2);
    }

    public static function displayName(?User $user): string
    {
        if (!$user) return 'Bidder';
        if ($user->customer && $user->customer->name) {
            return trim($user->customer->name . ' ' . ($user->customer->surname ?? ''));
        }
        return $user->username ?: ('User #' . $user->id);
    }
}
