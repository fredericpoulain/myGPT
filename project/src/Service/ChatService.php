<?php

namespace App\Service;

class ChatService
{
    public function getResponse($openAIClient, $model, $chatSession)
    {

        $result = $openAIClient->chat()->create([
            'model' => $model,
            'messages' => $chatSession['messages']
        ]);
        return $result['choices'][0]['message']['content'] ?? '';
    }
}