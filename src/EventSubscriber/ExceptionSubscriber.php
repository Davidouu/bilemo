<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof HttpException) {
            $statusCode = $exception->getStatusCode();
            $message = $exception->getMessage();

            if ($statusCode === 404) {
                $message = "Ressource non trouvée";
            }

            $data = [
                'status'  => $statusCode,
                'message' => $message
            ];

            $event->setResponse(new JsonResponse($data));
        } else {
            $data = [
                'status'  => 500,
                'message' => "Erreur côté serveur"
            ];

            $event->setResponse(new JsonResponse($data));
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }
}
