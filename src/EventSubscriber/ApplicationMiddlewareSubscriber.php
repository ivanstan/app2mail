<?php

namespace App\EventSubscriber;

use App\Controller\DefaultController;
use App\Entity\Application;
use App\Repository\ApplicationRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class ApplicationMiddlewareSubscriber implements EventSubscriberInterface
{
    protected ApplicationRepository $repository;

    public function __construct(ApplicationRepository $repository)
    {
        $this->repository = $repository;
    }

    protected function isMiddleware(Request $request): bool
    {
        return $request->attributes->get('_controller') === DefaultController::class . '::application';
    }

    protected function getApplication(Request $request): Application
    {
        $uuid = $request->attributes->get('uuid');

        $application = $this->repository->find($uuid);

        if (!$application) {
            throw new NotFoundHttpException(\sprintf('Application %s not found.', $uuid));
        }

        return $application;
    }


    protected function throwableToArray(\Throwable $throwable): array
    {
        return [
            'code' => $throwable->getCode(),
            'file' => $throwable->getFile() . ':' . $throwable->getLine(),
            'message' => $throwable->getMessage(),
            'trace' => $throwable->getTrace(),
        ];
    }

    protected function setJsonResponse(RequestEvent $event, array $response): void
    {
        $event->setResponse(
            new JsonResponse(
                [
                    'response' => $response,
                ],
                Response::HTTP_OK,
                [
                    'Access-Control-Allow-Origin' => '*',
                ]
            )
        );
    }
}