<?php

namespace App\Controller;

use App\Repository\UserClientRepository;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserClientController extends AbstractController
{
    #[Route('/api/clients', name: 'app_user_client', methods: ['GET'])]
    public function index(Request $request, UserClientRepository $userClientRepository, SerializerInterface $serializer): JsonResponse
    {
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 10);

        $userClients = $userClientRepository->paginateUserClients($page, $limit, $this->getUser());
        $jsonUserClients = $serializer->serialize($userClients, 'json');
        
        return new JsonResponse($jsonUserClients, Response::HTTP_OK, [], true);
    }

    #[Route('/api/clients/{id}', name: 'app_user_client_show', methods: ['GET'])]
    public function show($id, UserClientRepository $userClientRepository, SerializerInterface $serializer): JsonResponse
    {
        $userClient = $userClientRepository->findOneBy(['id' => $id, 'user' => $this->getUser()]);
        $jsonUserClient = $serializer->serialize($userClient, 'json');
        
        return new JsonResponse($jsonUserClient, Response::HTTP_OK, [], true);
    }
}
