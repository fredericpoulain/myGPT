<?php

namespace App\Service;

class CreateArrayService
{
    public function CreateArray(array $inputArray): array
    {
        $outputArray = [
            "sessionId" => $inputArray["sessionId"],
            "messages" => []
        ];
        $tempUserMessage = "";


        foreach ($inputArray["messages"] as $message) {
            if ($message["role"] === "user") {
                // Stocker le message de l'utilisateur temporairement
                $tempUserMessage = $message["content"];
            } elseif ($message["role"] === "system" && !empty($tempUserMessage)) {
                // Ajouter la paire de messages au tableau de sortie
                $outputArray["messages"][] = [
                    "userMessage" => $tempUserMessage,
                    "messageGpt" => $message["content"]
                ];
                // Réinitialiser le message de l'utilisateur pour la prochaine paire
                $tempUserMessage = "";
            }
        }
//        dd($outputArray);
        return $outputArray;
    }
}