<?php 

namespace frontend\services;

use yii\httpclient\Client;

class ChatBotService
{
    private string $apiKey;
    private string $endpoint;

    public function __construct()
    {
        $this->apiKey = \Yii::$app->params['chatbotApiKey'];
        $this->endpoint = 'https://api.openai.com/v1/responses';
    }

    public function ask(string $message): string
    {
        $client = new Client();
        
        $response = $client->post(
        $this->endpoint,
        [
            'model' => 'gpt-4.1-mini',
            'input' => $message,
        ],
        [
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type'  => 'application/json',
        ]
    )->send();
        
        

        if (!$response->isOk) {
            throw new \RuntimeException('Chatbot API error');
        }

        return $response->data['output'][0]['content'][0]['text'] ?? '';
    }
}


?>