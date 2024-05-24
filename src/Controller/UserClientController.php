<?php

namespace App\Controller;

use App\Entity\UserClient;
use App\Repository\UserClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use OpenApi\Attributes as OA;

class UserClientController extends AbstractController
{
    /**
     * A list of all user clients
     *
     * This call returns a list of paginate user clients, default limit is 10, and default page is 1.
     */
    #[Route('/api/clients', name: 'app_user_client', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns a list of user clients',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: UserClient::class))
        )
    )]
    #[OA\Parameter(
        name: 'page',
        in: 'query',
        description: 'The page number to retrieve',
        schema: new OA\Schema(type: 'int')
    )]
    #[OA\Parameter(
        name: 'limit',
        in: 'query',
        description: 'The number of items to retrieve per page',
        schema: new OA\Schema(type: 'int')
    )]
    #[OA\Tag(name: 'User clients')]
    public function index(
        Request $request,
        UserClientRepository $userClientRepository,
        SerializerInterface $serializer,
        TagAwareCacheInterface $cache
    ): JsonResponse {
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 10);

        $idCache = "allUserClients-" . $page . "-" . $limit;

        $jsonUserClients = $cache->get(
            $idCache,
            function (ItemInterface $item) use ($userClientRepository, $page, $limit, $serializer) {
                $item->tag("clientsCache");
                $item->expiresAfter(86400);
                $context = SerializationContext::create();
                $mobileList = $userClientRepository->paginateUserClients($page, $limit, $this->getUser());

                return $serializer->serialize($mobileList, 'json', $context);
            }
        );

        return new JsonResponse($jsonUserClients, Response::HTTP_OK, [], true);
    }

    /**
     * Show a user client
     *
     * This call returns a user client by id.
     */
    #[Route('/api/clients/{id}', name: 'app_user_client_show', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns a user client',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: UserClient::class))
        )
    )]
    #[OA\Tag(name: 'User clients')]
    public function show(
        UserClient $userClient,
        SerializerInterface $serializer
    ): JsonResponse {
        if ($userClient->getUser() !== $this->getUser()) {
            return new JsonResponse(null, JsonResponse::HTTP_FORBIDDEN);
        }

        $jsonUserClient = $serializer->serialize($userClient, 'json');

        return new JsonResponse($jsonUserClient, Response::HTTP_OK, [], true);
    }

    /**
     * Create a user client
     *
     * This call creates a user client.
     */
    #[Route('/api/clients', name: 'app_user_client_create', methods: ['POST'])]
    #[OA\Response(
        response: 201,
        description: 'Returns status 201 : created',
    )]
    #[OA\Tag(name: 'User clients')]
    public function create(
        Request $request,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGenerator,
        TagAwareCacheInterface $cache
    ): JsonResponse {
        $cache->invalidateTags(["clientsCache"]);

        $userClient = $serializer->deserialize($request->getContent(), UserClient::class, 'json');

        $errors = $validator->validate($userClient);

        if ($errors->count() > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }

        $userClient->setUser($this->getUser());

        $entityManager->persist($userClient);
        $entityManager->flush();

        $jsonUserClient = $serializer->serialize($userClient, 'json');

        $location = $urlGenerator->generate(
            'app_user_client_show',
            ['id' => $userClient->getId()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        return new JsonResponse($jsonUserClient, Response::HTTP_CREATED, ["Location" => $location], true);
    }

    /**
     * Edit a user client
     *
     * This call edits a user client by id.
     */
    #[Route('/api/clients/{id}', name: 'app_user_client_edit', methods: ['PUT'])]
    #[OA\Response(
        response: 200,
        description: 'Returns a user client',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: UserClient::class))
        )
    )]
    #[OA\Tag(name: 'User clients')]
    public function edit(
        UserClient $userClient,
        Request $request,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        EntityManagerInterface $entityManager,
        TagAwareCacheInterface $cache
    ): JsonResponse {
        if ($userClient->getUser() !== $this->getUser()) {
            return new JsonResponse(null, JsonResponse::HTTP_FORBIDDEN);
        }

        $cache->invalidateTags(["clientsCache"]);

        $newUserClinetDatas = $serializer->deserialize($request->getContent(), UserClient::class, 'json');

        $userClient->setEmail($newUserClinetDatas->getEmail());
        $userClient->setFirstname($newUserClinetDatas->getFirstname());
        $userClient->setLastname($newUserClinetDatas->getLastname());

        $errors = $validator->validate($userClient);

        if ($errors->count() > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }

        $entityManager->persist($userClient);
        $entityManager->flush();

        $jsonUserClient = $serializer->serialize($userClient, 'json');

        return new JsonResponse($jsonUserClient, JsonResponse::HTTP_OK, [], true);
    }

    /**
     * Delete a user client
     *
     * This call deletes a user client by id.
     */
    #[Route('/api/clients/{id}', name: 'app_user_client_delete', methods: ['DELETE'])]
    #[OA\Response(
        response: 204,
        description: 'Returns status 204 : no content',
    )]
    #[OA\Tag(name: 'User clients')]
    public function delete(
        UserClient $userClient,
        EntityManagerInterface $entityManager,
        TagAwareCacheInterface $cache
    ): JsonResponse {
        if ($userClient->getUser() !== $this->getUser()) {
            return new JsonResponse(null, JsonResponse::HTTP_FORBIDDEN);
        }

        $cache->invalidateTags(["clientsCache"]);

        $entityManager->remove($userClient);
        $entityManager->flush();

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
