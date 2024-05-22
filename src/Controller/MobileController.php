<?php

namespace App\Controller;

use App\Entity\Mobile;
use App\Repository\MobileRepository;
use JMS\Serializer\SerializationContext;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class MobileController extends AbstractController
{
    #[Route('/api/mobiles', name: 'app_mobile', methods: ['GET'])]
    public function index(
        Request $request,
        MobileRepository $mobileRepository,
        SerializerInterface $serializer,
        TagAwareCacheInterface $cache
    ): JsonResponse {
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 10);

        $idCache = "allMobiles-" . $page . "-" . $limit;

        $jsonMobiles = $cache->get(
            $idCache,
            function (ItemInterface $item) use ($mobileRepository, $page, $limit, $serializer) {
                $item->tag("mobilesCache");
                $item->expiresAfter(86400);
                $context = SerializationContext::create();
                $bookList = $mobileRepository->paginateMobiles($page, $limit);

                return $serializer->serialize($bookList, 'json', $context);
            }
        );

        return new JsonResponse($jsonMobiles, Response::HTTP_OK, [], true);
    }

    #[Route('/api/mobiles/{id}', name: 'app_mobile_show', methods: ['GET'])]
    public function show(Mobile $mobile, SerializerInterface $serializer): JsonResponse
    {
        $jsonMobile = $serializer->serialize($mobile, 'json');

        return new JsonResponse($jsonMobile, Response::HTTP_OK, [], true);
    }
}
