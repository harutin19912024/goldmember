<?php 

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use frontend\services\ChatBotService;

class ChatController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionAsk()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $message = Yii::$app->request->post('message');
        if (!$message) {
            return ['error' => 'Message is required'];
        }

        try {
            $bot = new ChatBotService();
            $reply = $bot->ask($message);

            return [
                'success' => true,
                'reply' => $reply,
            ];
        } catch (\Throwable $e) {
            print_r($e);die;
            Yii::error($e->getMessage(), 'chatbot');

            return [
                'success' => false,
                'error' => 'Chatbot unavailable',
            ];
        }
    }
}


?>