<?php

namespace App\Controller;

use App\Entity\Mobile;
use App\Repository\MobileRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;

class MobileController extends AbstractController
{
    #[Route('/api/mobiles', name: 'app_mobile', methods: ['GET'])]
    public function index(Request $request, MobileRepository $mobileRepository, SerializerInterface $serializer): JsonResponse
    {
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 10);

        $mobiles = $mobileRepository->paginateMobiles($page, $limit);
        $jsonMobiles = $serializer->serialize($mobiles, 'json');

        return new JsonResponse($jsonMobiles, Response::HTTP_OK, [], true);
    }

    #[Route('/api/mobiles/{id}', name: 'app_mobile_show', methods: ['GET'])]
    public function show(Mobile $mobile, SerializerInterface $serializer): JsonResponse
    {
        $jsonMobile = $serializer->serialize($mobile, 'json');

        return new JsonResponse($jsonMobile, Response::HTTP_OK, [], true);
    }
}
