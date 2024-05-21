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

        $mobiles = $userClientRepository->paginateUserClients($page, $limit, $this->getUser());
        $jsonMobiles = $serializer->serialize($mobiles, 'json');
        
        return new JsonResponse($jsonMobiles, Response::HTTP_OK, [], true);
    }
}
