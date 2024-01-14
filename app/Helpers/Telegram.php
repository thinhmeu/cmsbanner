<?php
namespace App\Helpers;

use GuzzleHttp\Client;

class Telegram{
    public static function sendMessage($text = ''){
        if (!empty($text)){
            $token = env("TELEGRAM_BOT_TOKEN");
            $chatId = env("TELEGRAM_CHAT_GROUP_ID");

            $client = new Client();

            $response = $client->post("https://api.telegram.org/bot{$token}/sendMessage", [
                'form_params' => [
                    'chat_id' => $chatId,
                    'text' => $text,
                ],
            ]);

            $result = json_decode($response->getBody(), true);

            if ($result['ok']) {
                return 'Message sent!';
            } else {
                return 'Failed to send message.';
            }
        }
    }
}
