<?php

namespace frontend\controllers;

use Yii;
use yii\db\Query;
use yii\web\Controller;
use yii\web\Response;
use frontend\services\ChatBotService;

class ChatController extends Controller
{
    public function actionAsk()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        // Require login so we can throttle per real user.
        if (Yii::$app->user->isGuest) {
            Yii::$app->response->statusCode = 401;
            return ['success' => false, 'error' => Yii::t('app', 'Please log in to use the chat.')];
        }

        $message = trim((string) Yii::$app->request->post('message', ''));
        if ($message === '') {
            return ['success' => false, 'error' => Yii::t('app', 'Message is required.')];
        }

        $maxLen = (int) (Yii::$app->params['chatbotMaxPromptLength'] ?? 800);
        if (mb_strlen($message) > $maxLen) {
            return ['success' => false, 'error' => Yii::t('app', 'Message too long (max {n} characters).', ['n' => $maxLen])];
        }

        $userId = (int) Yii::$app->user->id;
        $ip     = Yii::$app->request->userIP ?? '0.0.0.0';

        // Quota check — count this user's messages in the last 24h.
        $dailyLimit = (int) (Yii::$app->params['chatbotDailyLimit'] ?? 0);
        if ($dailyLimit > 0) {
            $used = (int) (new Query())
                ->from('chat_usage')
                ->where(['user_id' => $userId])
                ->andWhere(['>=', 'created_at', date('Y-m-d H:i:s', time() - 86400)])
                ->count();
            if ($used >= $dailyLimit) {
                Yii::$app->response->statusCode = 429;
                return [
                    'success'   => false,
                    'error'     => Yii::t('app', "You've reached your daily limit of {n} messages. Try again tomorrow.", ['n' => $dailyLimit]),
                    'remaining' => 0,
                ];
            }
        }

        $bot = new ChatBotService();
        if (!$bot->isConfigured()) {
            Yii::$app->response->statusCode = 503;
            return ['success' => false, 'error' => Yii::t('app', 'Chat is temporarily unavailable.')];
        }

        try {
            $result = $bot->ask($message);
        } catch (\Throwable $e) {
            Yii::error('chatbot error: ' . $e->getMessage(), 'chatbot');
            Yii::$app->response->statusCode = 502;
            return ['success' => false, 'error' => Yii::t('app', 'Chat is temporarily unavailable.')];
        }

        Yii::$app->db->createCommand()->insert('chat_usage', [
            'user_id'    => $userId,
            'ip'         => $ip,
            'message'    => mb_substr($message, 0, 2000),
            'tokens_in'  => $result['tokens_in'],
            'tokens_out' => $result['tokens_out'],
            'created_at' => date('Y-m-d H:i:s'),
        ])->execute();

        $remaining = $dailyLimit > 0 ? max(0, $dailyLimit - $used - 1) : null;

        return [
            'success'   => true,
            'reply'     => $result['reply'],
            'remaining' => $remaining,
        ];
    }
}
