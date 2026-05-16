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

        // Validate channel format: must be "auction-{id}"
        if (!preg_match('/^auction-(\d+)$/', $channel, $m)) {
            throw new BadRequestHttpException('Invalid channel.');
        }

        $auctionId = (int) $m[1];
        $auction   = Auction::findOne($auctionId);
        if (!$auction) {
            throw new BadRequestHttpException('Auction not found.');
        }

        // Only allow joining live auctions (or upcoming — token pre-fetch is fine)
        $now = time();
        $end = $auction->end_date ? strtotime($auction->end_date) : null;
        if ($end && $now > $end) {
            Yii::$app->response->statusCode = 403;
            return ['error' => 'Auction has ended.'];
        }

        $appId   = Yii::$app->params['agoraAppId'];
        $appCert = Yii::$app->params['agoraAppCertificate'];

        // Use the authenticated user's ID as UID (avoids collisions, enables identification)
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
            'token'   => $token,
            'uid'     => $uid,
            'appid'   => $appId,
            'channel' => $channel,
            'role'    => 'audience',
        ];
    }
}
