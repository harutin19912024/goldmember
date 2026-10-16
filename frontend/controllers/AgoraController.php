<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\BadRequestHttpException;
use yii\filters\AccessControl;
use common\components\agora\RtcTokenBuilder2;
use common\models\Bid;
use backend\models\Auction;
use yii\filters\VerbFilter;

class AgoraController extends Controller
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'place-bid' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow'   => true,
                        'roles'   => ['@'],  // authenticated users only
                    ],
                ],
                'denyCallback' => function () {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    Yii::$app->response->statusCode = 401;
                    return ['error' => 'Authentication required'];
                },
            ],
        ]);
    }

    public function actionGetToken($channel)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $auction = $this->resolveAuctionFromChannel($channel);

        $now = time();
        $end = $auction->end_date ? strtotime($auction->end_date) : null;
        if ($end && $now > $end) {
            Yii::$app->response->statusCode = 403;
            return ['error' => 'Auction has ended.'];
        }

        $appId   = Yii::$app->params['agoraAppId'];
        $appCert = Yii::$app->params['agoraAppCertificate'];

        $uid        = (int) Yii::$app->user->id;
        $expireTime = time() + 3600;

        // Issue a PUBLISHER token so the viewer can opt-in to "Share my camera".
        // The client-side role (`audience` by default, switches to `host` via the
        // UI button) governs whether they actually broadcast.
        $token = RtcTokenBuilder2::buildTokenWithUid(
            $appId,
            $appCert,
            $channel,
            $uid,
            RtcTokenBuilder2::ROLE_PUBLISHER,
            $expireTime
        );

        return [
            'token'    => $token,
            'uid'      => $uid,
            'appid'    => $appId,
            'channel'  => $channel,
            'role'     => 'audience',
            'host'     => $this->describeHost($auction),
        ];
    }

    /**
     * Bulk lookup of user info by uid — used to render participant avatars
     * (name + initials + a deterministic per-uid colour) on the auction page.
     */
    public function actionUserInfo($uids = '')
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $ids = array_filter(array_map('intval', explode(',', (string)$uids)));
        if (empty($ids)) return [];

        $users = \common\models\User::find()
            ->with('customer')
            ->where(['id' => $ids])
            ->all();

        $out = [];
        foreach ($users as $u) {
            $name = $u->username ?: ('User #' . $u->id);
            if ($u->customer && $u->customer->name) {
                $name = trim($u->customer->name . ' ' . ($u->customer->surname ?? ''));
            }
            $out[(int)$u->id] = self::profileFor((int)$u->id, $name);
        }
        // Fill in any uids we couldn't find so the client doesn't keep re-asking.
        foreach ($ids as $id) {
            if (!isset($out[$id])) {
                $out[$id] = self::profileFor($id, 'User #' . $id);
            }
        }
        return $out;
    }

    private static function profileFor(int $uid, string $name): array
    {
        $initial = mb_strtoupper(mb_substr($name, 0, 1) ?: '?');
        // Deterministic HSL colour per uid — same uid always renders the same hue.
        $hue = ($uid * 47) % 360;
        return [
            'uid'     => $uid,
            'name'    => $name,
            'initial' => $initial,
            'color'   => "hsl($hue, 55%, 45%)",
        ];
    }

    /**
     * Returns the auction's host (uid + display name) so the audience UI can
     * label the publisher correctly and show "waiting for host" before the
     * stream starts.
     */
    /**
     * Initial / refresh bid snapshot for an auction.
     */
    public function actionBids($auction_id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $auction = Auction::findOne((int)$auction_id);
        if (!$auction) {
            Yii::$app->response->statusCode = 404;
            return ['error' => 'Auction not found.'];
        }
        return Bid::snapshot($auction);
    }

    /**
     * Place a bid. POST JSON or form with auction_id and amount. Returns the
     * new bid plus the refreshed snapshot.
     */
    public function actionPlaceBid()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $req = Yii::$app->request;
        $auctionId = (int)($req->post('auction_id') ?: $req->getBodyParam('auction_id'));
        $amount    = (float)($req->post('amount')     ?: $req->getBodyParam('amount'));

        $auction = Auction::findOne($auctionId);
        if (!$auction) {
            Yii::$app->response->statusCode = 404;
            return ['error' => 'Auction not found.'];
        }

        // Auction must be live (between start and end).
        $now = time();
        $start = $auction->start_date ? strtotime($auction->start_date) : 0;
        $end   = $auction->end_date   ? strtotime($auction->end_date)   : null;
        if ($start && $now < $start) {
            Yii::$app->response->statusCode = 409;
            return ['error' => 'Auction has not started yet.'];
        }
        if ($end && $now > $end) {
            Yii::$app->response->statusCode = 409;
            return ['error' => 'Auction has ended.'];
        }

        // Admin who created the auction can't bid in their own auction.
        $userId = (int) Yii::$app->user->id;
        if ((int)$auction->user_id === $userId) {
            Yii::$app->response->statusCode = 403;
            return ['error' => 'Hosts cannot bid in their own auction.'];
        }

        $high = Bid::highestAmount($auction->id);
        $minNext = Bid::nextMinimum($auction, $high);
        if ($amount + 0.0001 < $minNext) {
            Yii::$app->response->statusCode = 400;
            return [
                'error'        => 'Bid must be at least ' . number_format($minNext, 2, '.', ''),
                'next_minimum' => $minNext,
            ];
        }

        $bid = new Bid([
            'auction_id' => $auction->id,
            'user_id'    => $userId,
            'amount'     => $amount,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        if (!$bid->save()) {
            Yii::$app->response->statusCode = 500;
            return ['error' => 'Could not save bid.', 'details' => $bid->errors];
        }

        // Keep auction.current_price in sync for cheap reads — only if the
        // column has been added to the schema. Skip silently otherwise.
        if ($auction->hasAttribute('current_price')) {
            $auction->current_price = $amount;
            $auction->save(false, ['current_price']);
        }

        return [
            'ok'       => true,
            'bid'      => [
                'id'         => (int)$bid->id,
                'user_id'    => (int)$bid->user_id,
                'amount'     => (float)$bid->amount,
                'name'       => Bid::displayName(Yii::$app->user->identity),
                'created_at' => $bid->created_at,
            ],
            'snapshot' => Bid::snapshot($auction),
        ];
    }

    public function actionHostInfo($channel)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $auction = $this->resolveAuctionFromChannel($channel);
        return $this->describeHost($auction);
    }

    private function resolveAuctionFromChannel(string $channel): Auction
    {
        if (!preg_match('/^auction-(\d+)$/', $channel, $m)) {
            throw new BadRequestHttpException('Invalid channel.');
        }
        $auction = Auction::findOne((int)$m[1]);
        if (!$auction) {
            throw new BadRequestHttpException('Auction not found.');
        }
        return $auction;
    }

    private function describeHost(Auction $auction): array
    {
        $hostUid  = (int)($auction->user_id ?: 0);
        $hostName = 'Host';
        if ($auction->user) {
            $hostName = $auction->user->username ?: ('User #' . $hostUid);
        }
        return ['uid' => $hostUid, 'name' => $hostName];
    }
}
