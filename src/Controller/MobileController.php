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
use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Annotation\Model;

class MobileController extends AbstractController
{
    /**
     * A list of all mobiles
     *
     * This call returns a list of paginate mobiles, default limit is 10, and default page is 1.
     */
    #[Route('/api/mobiles', name: 'app_mobile', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns a list of mobiles',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Mobile::class))
        )
    )]
    #[OA\Tag(name: 'Mobiles')]
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
                $mobileList = $mobileRepository->paginateMobiles($page, $limit);

                return $serializer->serialize($mobileList, 'json', $context);
            }
        );

        return new JsonResponse($jsonMobiles, Response::HTTP_OK, [], true);
    }

    /**
     * Show a mobile
     *
     * This call returns a mobile by id.
     */
    #[Route('/api/mobiles/{id}', name: 'app_mobile_show', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns a mobile by id',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Mobile::class))
        )
    )]
    #[OA\Tag(name: 'Mobiles')]
    public function show(Mobile $mobile, SerializerInterface $serializer): JsonResponse
    {
        $jsonMobile = $serializer->serialize($mobile, 'json');

        return new JsonResponse($jsonMobile, Response::HTTP_OK, [], true);
    }
}
