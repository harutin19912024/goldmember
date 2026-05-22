<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\BadRequestHttpException;
use yii\filters\AccessControl;
use common\components\agora\RtcTokenBuilder2;
use backend\models\Auction;

class AgoraController extends Controller
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
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

        $token = RtcTokenBuilder2::buildTokenWithUid(
            $appId,
            $appCert,
            $channel,
            $uid,
            RtcTokenBuilder2::ROLE_SUBSCRIBER,
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
     * Returns the auction's host (uid + display name) so the audience UI can
     * label the publisher correctly and show "waiting for host" before the
     * stream starts.
     */
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
