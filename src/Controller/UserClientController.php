<?php

namespace App\Controller;

use App\Entity\UserClient;
use App\Repository\UserClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class UserClientController extends AbstractController
{
    #[Route('/api/clients', name: 'app_user_client', methods: ['GET'])]
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

    #[Route('/api/clients/{id}', name: 'app_user_client_show', methods: ['GET'])]
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

    #[Route('/api/clients', name: 'app_user_client_create', methods: ['POST'])]
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

    #[Route('/api/clients/{id}', name: 'app_user_client_edit', methods: ['PUT'])]
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

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

    #[Route('/api/clients/{id}', name: 'app_user_client_delete', methods: ['DELETE'])]
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
