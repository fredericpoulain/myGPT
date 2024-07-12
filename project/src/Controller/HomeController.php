<?php

namespace App\Controller;

use App\Entity\Chat;
use App\Repository\ChatRepository;
use App\Service\ChatService;
use App\Service\CreateArrayService;
use App\Service\FormatTextService;
use App\Service\ImageGenerateService;
use Doctrine\ORM\EntityManagerInterface;
use OpenAI\Client;
use Parsedown;
use Random\RandomException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;

class HomeController extends AbstractController
{
    private $parsedown;

    public function __construct(private readonly Client $openAIClient)
    {
        $this->parsedown = new Parsedown();
    }

    #[Route('/', name: 'app_home')]
    public function home(ChatRepository $chatRepository): Response
    {
        $chats = [];
        if ($this->getUser()) {
            $chats = $chatRepository->findBy(
                ['user' => $this->getUser()],
                ['createdAt' => 'DESC']
            );
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

        return $this->json($arrayData);

    }

    /**
     * @throws RandomException
     */
    #[Route('/addChat', name: 'app_addChat')]
    public function addChat(
        SessionInterface       $session,
        Request                $request,
        FormatTextService      $formatTextService,
        CreateArrayService     $createArrayService,
        EntityManagerInterface $entityManager,
        ChatRepository         $chatRepository,
        ChatService            $chatService,
        ImageGenerateService   $imageGenerateService
    ): JsonResponse
    {
        try {
            // On récupère la session et les données de la requête
            $chatSession = $session->get('chatSession', []);
            $data = json_decode($request->getContent());
            $messageUser = $data->message ?? '';
            $model = $data->model ?? '';

            // On vérifie si le modèle d'IA est autorisé
            $authorizedModels = ["gpt-4o", "gpt-4-turbo", "gpt-3.5-turbo", "dall-e-3"];

            if (!in_array($model, $authorizedModels)) {
                return $this->json(['isSuccessfull' => false, 'data' => []]);
            }

            $content = "Vous êtes un assistant expert en langages informatique et vous utilisez les meilleures pratiques de codage. Les solutions que vous apportez sont systématiquement les plus simples, tout en respectant le principe SOLID et de la 'Clean Architecture'.";
            // On initialise la session si elle est vide
            if (empty($chatSession)) {
                $chatSession = [
                    'sessionId' => bin2hex(random_bytes(16)),
                    'messages' => [
                        ['role' => 'system', 'content' => $content]
                    ]
                ];
            }

            // On ajoute le message de l'utilisateur
            $chatSession['messages'][] = ['role' => 'user', 'content' => $messageUser];

            //Appel de l'API et reception de la réponse


            if ($model === "dall-e-3") {
                $messageGpt = $imageGenerateService->getResponse($this->openAIClient, $model, $messageUser);
            } else {
                //Si c'est un chat
                $output = $chatService->getResponse($this->openAIClient, $model, $chatSession);
                // Formater la réponse et mettre à jour la session
                $messageGpt = $formatTextService->formatText($output, $this->parsedown);
            }

            $chatSession['messages'][] = ['role' => 'assistant', 'content' => $messageGpt];



            $session->set('chatSession', $chatSession);
            // Enregistrement en base de données si l'utilisateur est connecté
            if ($this->getUser()) {

                $sessionId = $chatSession['sessionId'];
                $chat = $chatRepository->findOneBy(['sessionid' => $sessionId]);

                //C'est une nouvelle discussion
                if (!$chat) {
                    $chat = new Chat();
                    $chat->setUser($this->getUser());
                    $chat->setSessionId($sessionId);
                    $chat->setSessiondata($chatSession);
                    $chat->setName(substr($chatSession['messages'][1]['content'], 0, 50) . '...');
                    $entityManager->persist($chat);
                    $entityManager->flush();

                    //si chat se trouve en base de donnée
                } else {
                    $chat->setSessiondata($chatSession);
                    $entityManager->flush();
                }
            }

            // Retourner la réponse JSON
            return $this->json([
                'isSuccessfull' => !empty($messageGpt),
                'data' => $createArrayService->CreateArray($chatSession)
            ]);
        } catch (\Exception $e) {
            // Gérer les exceptions et retourner une réponse d'erreur
            return $this->json([
                'isSuccessfull' => false,
                'error' => $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    #[Route('/loadChatSession/{sessionid}', name: 'app_loadChatSession')]
    public function loadChatSession(Chat $chat, SessionInterface $session)
    {
        if ($this->getUser() && $chat->getUser()) {
            if ($this->getUser()->getId() === $chat->getUser()->getId()) {
                $session->set('chatSession', $chat->getSessiondata());
            }
        }
        return $this->redirectToRoute('app_home');
    }

    #[Route('/supprimer-conversation/{sessionid}', name: 'app_deleteChatSession')]
    public function deleteChatSession(Chat $chat, EntityManagerInterface $entityManager, SessionInterface $session): RedirectResponse
    {
        if ($this->getUser() && $chat->getUser()) {
            if ($this->getUser()->getId() === $chat->getUser()->getId()) {
                $chatSession = $session->get('chatSession', []);
                //Si la conversation supprimée est la même que celle de la session en cours, alors on vide aussi la session
                if (isset($chatSession['sessionId']) && $chatSession['sessionId'] === $chat->getSessionid()) {
                    $session->remove('chatSession');
                }
                $entityManager->remove($chat);
                $entityManager->flush();
            }
        }
        return $this->redirectToRoute('app_home');
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
