<?php

namespace App\Service;

class ImageGenerateService
{
    public function getResponse($openAIClient, $model, $messageUser)
    {
//        $response = $openAIClient->images()->create([
//            'model' => $model,
//            'prompt' => $messageUser,
//            'n' => 1,
//            'size' => '1024x1024',
//            'response_format' => 'url',
//        ]);
//        $url = $response['data'][0]['url'];
        $url = "https://oaidalleapiprodscus.blob.core.windows.net/private/org-KGbig54TdhRh60AjTangyhTS/user-0uyGvNQdxGVpj1G5Dd4c1LNL/img-oxJJ2BORWtUcVQTRSy99z04v.png?st=2024-07-12T15%3A48%3A32Z&se=2024-07-12T17%3A48%3A32Z&sp=r&sv=2023-11-03&sr=b&rscd=inline&rsct=image/png&skoid=6aaadede-4fb3-4698-a8f6-684d7786b067&sktid=a48cca56-e6da-484e-a814-9c849652bcb3&skt=2024-07-12T12%3A55%3A48Z&ske=2024-07-13T12%3A55%3A48Z&sks=b&skv=2023-11-03&sig=fWz9r%2B9htdSvetrGGpbZ/HsFgEsjWdY3y1QJ2xysQZ0%3D";

        return "<img class='imgGenerate' src='$url' alt='Image qui correspond à cette demande : $messageUser'/>";
    }
}