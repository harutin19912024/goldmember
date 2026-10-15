<?php

namespace backend\controllers;

use backend\models\Auction;
use backend\models\AuctionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use common\components\agora\RtcTokenBuilder2;
use Yii;

/**
 * AuctionController implements the CRUD actions for Auction model.
 */
class AuctionController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete'        => ['POST'],
                        'close-auction' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Auction models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new AuctionSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Auction model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Auction model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Auction();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                if (empty($model->user_id) && !Yii::$app->user->isGuest) {
                    $model->user_id = Yii::$app->user->id;
                }
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Auction created. Lot number: ') . $model->lot_number);
                    return $this->redirect(['update', 'id' => $model->id]);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Auction model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }
    
    /**
     * Admin closes the auction and marks a winner. POST with auction_id and
     * optional bid_id (defaults to highest bid). Pulls end_date forward to
     * now so the public "ended" status takes effect.
     */
    public function actionCloseAuction()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (Yii::$app->user->isGuest) {
            Yii::$app->response->statusCode = 401;
            return ['error' => 'Authentication required.'];
        }
        if ((int)Yii::$app->user->identity->role !== 0) {
            Yii::$app->response->statusCode = 403;
            return ['error' => 'Only admins may close auctions.'];
        }

        $req = Yii::$app->request;
        $auctionId = (int) ($req->post('auction_id') ?: $req->getBodyParam('auction_id'));
        $bidId     = (int) ($req->post('bid_id')     ?: $req->getBodyParam('bid_id'));

        $auction = Auction::findOne($auctionId);
        if (!$auction) {
            Yii::$app->response->statusCode = 404;
            return ['error' => 'Auction not found.'];
        }
        if (\common\models\Bid::safeAttr($auction, 'closed_at')) {
            Yii::$app->response->statusCode = 409;
            return ['error' => 'Auction is already closed.'];
        }

        $bid = null;
        if ($bidId) {
            $bid = \common\models\Bid::findOne(['id' => $bidId, 'auction_id' => $auctionId]);
            if (!$bid) {
                Yii::$app->response->statusCode = 404;
                return ['error' => 'Bid not found for this auction.'];
            }
        } else {
            $bid = \common\models\Bid::find()
                ->where(['auction_id' => $auctionId])
                ->orderBy(['amount' => SORT_DESC, 'id' => SORT_DESC])
                ->one();
        }

        $now = date('Y-m-d H:i:s');
        if ($auction->hasAttribute('closed_at')) {
            $auction->closed_at = $now;
        }
        if ($bid) {
            if ($auction->hasAttribute('winner_user_id')) $auction->winner_user_id = (int)$bid->user_id;
            if ($auction->hasAttribute('winning_bid_id')) $auction->winning_bid_id = (int)$bid->id;
            if ($auction->hasAttribute('winning_amount')) $auction->winning_amount = (float)$bid->amount;
            if ($auction->hasAttribute('current_price'))  $auction->current_price  = (float)$bid->amount;
        }
        // Ensure the public status sees the auction as ended even if the
        // winner columns aren't present yet.
        if ($auction->end_date && strtotime($auction->end_date) > time()) {
            $auction->end_date = $now;
        } elseif (!$auction->end_date) {
            $auction->end_date = $now;
        }
        if (!$auction->save(false)) {
            Yii::$app->response->statusCode = 500;
            return ['error' => 'Could not save auction.'];
        }

        // Build the snapshot, then guarantee the winner is filled from the
        // chosen bid even when the auction.winner_* columns aren't present yet.
        $snap = \common\models\Bid::snapshot($auction);
        $snap['closed'] = true;
        if ($bid && empty($snap['winner'])) {
            $bidder = \common\models\User::findOne($bid->user_id);
            $snap['winner'] = [
                'user_id' => (int)$bid->user_id,
                'name'    => \common\models\Bid::displayName($bidder),
                'amount'  => (float)$bid->amount,
            ];
        }

        return [
            'ok'       => true,
            'snapshot' => $snap,
        ];
    }

    public function actionBids($auction_id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (Yii::$app->user->isGuest) {
            Yii::$app->response->statusCode = 401;
            return ['error' => 'Authentication required.'];
        }
        $auction = Auction::findOne((int)$auction_id);
        if (!$auction) {
            Yii::$app->response->statusCode = 404;
            return ['error' => 'Auction not found.'];
        }
        return \common\models\Bid::snapshot($auction);
    }

    public function actionUserInfo($uids = '')
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (Yii::$app->user->isGuest) {
            Yii::$app->response->statusCode = 401;
            return ['error' => 'Authentication required.'];
        }
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
            $out[(int)$u->id] = self::buildProfile((int)$u->id, $name);
        }
        foreach ($ids as $id) {
            if (!isset($out[$id])) {
                $out[$id] = self::buildProfile($id, 'User #' . $id);
            }
        }
        return $out;
    }

    private static function buildProfile(int $uid, string $name): array
    {
        $initial = mb_strtoupper(mb_substr($name, 0, 1) ?: '?');
        $hue = ($uid * 47) % 360;
        return [
            'uid'     => $uid,
            'name'    => $name,
            'initial' => $initial,
            'color'   => "hsl($hue, 55%, 45%)",
        ];
    }

    public function actionGetToken($channel)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (Yii::$app->user->isGuest) {
            Yii::$app->response->statusCode = 401;
            return ['error' => 'Authentication required.'];
        }
        if (!preg_match('/^auction-(\d+)$/', $channel, $m)) {
            Yii::$app->response->statusCode = 400;
            return ['error' => 'Invalid channel.'];
        }
        $auction = Auction::findOne((int)$m[1]);
        if (!$auction) {
            Yii::$app->response->statusCode = 404;
            return ['error' => 'Auction not found.'];
        }
        // Only the admin who created the auction (or any admin role=0) may host.
        $currentUser = Yii::$app->user->identity;
        if ((int)$currentUser->role !== 0) {
            Yii::$app->response->statusCode = 403;
            return ['error' => 'Only an admin may host the stream.'];
        }

        $appId   = Yii::$app->params['agoraAppId'];
        $appCert = Yii::$app->params['agoraAppCertificate'];
        $uid     = (int)Yii::$app->user->id;

        $token = RtcTokenBuilder2::buildTokenWithUid(
            $appId,
            $appCert,
            $channel,
            $uid,
            RtcTokenBuilder2::ROLE_PUBLISHER,
            time() + 3600
        );

        return [
            'token'   => $token,
            'uid'     => $uid,
            'appid'   => $appId,
            'channel' => $channel,
            'role'    => 'host',
        ];
    }


    /**
     * Deletes an existing Auction model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Auction model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Auction the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Auction::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
