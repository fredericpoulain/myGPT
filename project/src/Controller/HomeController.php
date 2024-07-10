<?php

namespace App\Controller;

use App\Entity\Chat;
use App\Repository\ChatRepository;
use App\Service\CreateArrayService;
use App\Service\FormatTextService;
use Doctrine\ORM\EntityManagerInterface;
use OpenAI\Client;
use Parsedown;
use Random\RandomException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HomeController extends AbstractController
{
    private $parsedown;
    public function __construct(private readonly Client $openAIClient){
        $this->parsedown = new Parsedown();
    }
    #[Route('/', name: 'app_home')]
    public function home(SessionInterface $session): Response
    {
        $chats = [];
        if ($this->getUser()){

            $chats = $this->getUser()->getChats()->toArray();
        }
        return $this->render('home/home.html.twig', [
            'chats' => $chats,
        ]);
    }

    /**
     */
    #[Route('/getChat', name: 'app_getChat')]
    public function getChatSession(SessionInterface $session, CreateArrayService $createArrayService): JsonResponse
    {


        $chatSession = $session->get('chatSession', []);
        $arrayData = [];
        if (!empty($chatSession)) {
            $arrayData = $createArrayService->CreateArray($chatSession);
        }

        return $this->json($arrayData); // Retourner les données de la session de chat au format JSON

//        $messages = $chatSession['messages'] ?? [];
//        return $this->json(compact('messages'));
    }

    /**
     * @throws RandomException
     */
    #[Route('/addChat', name: 'app_addChat')]
    public function addChat(
        SessionInterface $session,
        Request $request,
        FormatTextService $formatTextService,
        CreateArrayService $createArrayService,
        EntityManagerInterface $entityManager,
        ChatRepository $chatRepository): JsonResponse
    {
//        $response = $this->openAIClient->models()->list();
//        dd($response);



        $chatSession = $session->get('chatSession', []);

        $content = $request->getContent();
        $data = json_decode($content);
        $messageUser = $data->message;

        // Initialiser la session si elle est vide
        if (empty($chatSession)) {
            $sessionId = bin2hex(random_bytes(16));
            $chatSession['sessionId'] = $sessionId;
            $chatSession['messages'] = [
                ['role' => 'system', 'content' => 'Vous êtes un assistant.']
            ];
        }

        // Ajouter le message de l'utilisateur à la liste des messages
        $chatSession['messages'][] = ['role' => 'user', 'content' => $messageUser];

        // Appel API OpenAI avec l'historique complet des messages
        $result = $this->openAIClient->chat()->create([
            'model' => 'gpt-3.5-turbo',
//            'model' => 'gpt-4o',
            'messages' => $chatSession['messages']
        ]);
        $output = $result['choices'][0]['message']['content'];
//        dd($result['choices'][0]['message']['content']);
        $messageGpt = $formatTextService->formatText($output, $this->parsedown);
        // Ajouter la réponse de GPT à la liste des messages
        $chatSession['messages'][] = ['role' => 'assistant', 'content' => $messageGpt];

        // Mettre à jour la session
        $session->set('chatSession', $chatSession);
//        dump($chatSession);
//        dd($createArrayService->CreateArray($chatSession));
        // Déterminer si la réponse a été réussie
        $isSuccessfull = !empty($messageGpt);


        //enregistrement en BDD
        //*********************
        if ($this->getUser()) {

            $sessionId = $chatSession['sessionId'];
            $chat = $chatRepository->findOneBy(['sessionid' => $sessionId]);

            //C'est une nouvelle discussion
            if (!$chat){
                $chat = new Chat();
                $chat->setUser($this->getUser());
                $chat->setSessionId($sessionId);
                $chat->setSessiondata($chatSession);
                $chat->setName(substr($chatSession['messages'][1]['content'], 0, 25) . '...');
                $entityManager->persist($chat);
                $entityManager->flush();

            //si chat se trouve en base de donnée
            }else{
                $chat->setSessiondata($chatSession);
                $entityManager->flush();
            }
        }

        //*********************
        //*********************




        // Retourner la réponse JSON
        return $this->json([
            'isSuccessfull' => $isSuccessfull,
            'data' => $createArrayService->CreateArray($chatSession)
        ]);
    }

    /**
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    #[Route('/resetSession', name: 'app_resetSession')]
    public function resetSession(SessionInterface $session): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        $session->remove('chatSession');
        return $this->redirectToRoute('app_home');
    }
}
