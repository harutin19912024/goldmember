<?php

namespace common\components;

use Yii;
use yii\helpers\Html;
use backend\models\Auction;
use common\models\Bid;
use common\models\User;

/**
 * Emails sent when an auction closes. Failures are logged, never thrown:
 * closing an auction must not depend on the mail server.
 */
class AuctionNotifier
{
    public static function sendWinnerEmail(Auction $auction, Bid $bid): bool
    {
        $user = User::findOne($bid->user_id);
        if (!$user || empty($user->email)) {
            return false;
        }
        $lot = $auction->lot_number ?: ('#' . $auction->id);
        $item = $auction->product->name ?? Yii::t('app', 'the auctioned item');
        $amount = number_format((float)$bid->amount, 0, '.', ',');
        $content = '<p>' . Yii::t('app', 'Congratulations, {name}!', ['name' => Html::encode(Bid::displayName($user))]) . '</p>'
            . '<p>' . Yii::t('app', 'You won lot {lot} ({item}) with a bid of ֏{amount}.', [
                'lot' => Html::encode($lot), 'item' => Html::encode($item), 'amount' => $amount,
            ]) . '</p>'
            . '<p>' . Yii::t('app', 'Our team will contact you shortly to arrange payment and delivery.') . '</p>';
        try {
            return Yii::$app->mailer->compose('email-layout', ['content' => $content])
                ->setFrom([Yii::$app->params['adminEmail'] ?? 'info@goldmember.am' => 'Goldmember'])
                ->setTo($user->email)
                ->setSubject(Yii::t('app', 'You won auction {lot}', ['lot' => $lot]))
                ->send();
        } catch (\Throwable $e) {
            Yii::warning("Auction {$auction->id} winner email failed: " . $e->getMessage(), __METHOD__);
            return false;
        }
    }
}
