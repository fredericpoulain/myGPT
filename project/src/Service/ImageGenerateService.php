<?php

namespace App\Service;

class ImageGenerateService
{
    public function getResponse($openAIClient, $model, $messageUser)
    {
        $response = $openAIClient->images()->create([
            'model' => $model,
            'prompt' => $messageUser,
            'n' => 1,
            'size' => '1024x1024',
            'response_format' => 'url',
        ]);
        $url = $response['data'][0]['url'];
        return "<img class='imgGenerate' src='$url' alt='Image qui correspond à cette demande : $messageUser'/>";
    }
}