<?php

namespace App\Controller;

use Random\RandomException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(): Response
    {

        return $this->render('home/home.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/infoUser', name: 'app_infoUser')]
    public function name(): JsonResponse
    {
        $name = 'Fred';
        $age = '39 ans';
        return $this->json(compact('name', 'age'));
    }
    #[Route('/messages', name: 'app_messages')]
    public function messages(): JsonResponse
    {
        $userMessage = "1 - can openai perform queries if mongodb uri is given? \n jdnldfjnldfnbltjhrtlk lk,gltrkgn lkt,htrl,h 2 - No, OpenAI cannot perform queries directly against a MongoDB database by itself. OpenAI is primarily an artificial intelligence language model that is designed to perform natural language processing tasks such as generating text or answering questions.";
        $gptMessage = "2 - No, OpenAI cannot perform queries directly against a MongoDB database by itself. \n OpenAI is primarily an artificial intelligence language model that is designed to perform natural language processing tasks such as generating text or answering questions. To execute queries against a MongoDB database, you need to use a driver library or ORM in your server-side code to establish a connection with the database and execute queries. Once you have the query results, you can then pass them to OpenAI for processing and analysis. For example, you could use a Node.js MongoDB driver such as Mongoose to connect to your MongoDB database, execute queries and retrieve data. Once you have the data, you could then pass it to OpenAI to perform natural language processing tasks like summarization or sentiment analysis. In summary, you would need to use both MongoDB driver and OpenAI API to execute queries against the database and perform natural language processing tasks on the results.";

        return $this->json(compact('userMessage', 'gptMessage'));
    }

    /**
     * @throws RandomException
     */
    #[Route('/getChat', name: 'app_getChat')]
    public function getChatSession(SessionInterface $session): JsonResponse
    {
//        $session->remove('chatSession');
        $chatSession = $session->get('chatSession', []);
        $messages = $chatSession['messages'] ?? [];
        return $this->json(compact('messages'));
    }

    /**
     * @throws RandomException
     */
    #[Route('/addChat', name: 'app_addChat')]
    public function addChat(SessionInterface $session, Request $request): JsonResponse
    {
        $chatSession = $session->get('chatSession', []);

        $content = $request->getContent();
        $data = json_decode($content, true);
        $messageUser = $data->message;

//        ******** APPEL API CHATGPT ********
        $messageGpt = "J suis chatGPT, voici mon message...";


        $isSuccessfull = !empty($messageGpt);

        if (empty($chatSession)){
            $sessionId = bin2hex(random_bytes(16));
            $chatSession['sessionId'] = $sessionId;
        }

        $chatSession['messages'][] = [
            'userMessage' => $messageUser,
            'messageGpt' => $messageGpt,
        ];
        $session->set('chatSession', $chatSession);
        dump($chatSession);
        return $this->json([
            'isSuccessfull' => $isSuccessfull,
            'data' => $chatSession
        ]);
    }
}
//        if (!empty($chatSession)){
//
//        }else{
//            $sessionId = bin2hex(random_bytes(16));
//
//            $chatSession = [
//                'sessionId' => $sessionId,
//                'messages' => [
//                    [
//                        'messageId' => 'msg-1',
//                        'sender' => 'user',
//                        'content' => 'Bonjour, comment ça va ?',
//                        'timestamp' => '2024-07-08T12:00:00Z'
//                    ],
//                    [
//                        'messageId' => 'msg-2',
//                        'sender' => 'chatgpt',
//                        'content' => 'Bonjour ! Je vais bien, merci. Comment puis-je vous aider aujourd\'hui ?',
//                        'timestamp' => '2024-07-08T12:00:05Z'
//                    ]
//                ]
//            ];
//        }