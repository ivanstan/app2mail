<?php

namespace App\EventSubscriber;

use App\Repository\ApplicationRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionSubscriber extends ApplicationMiddlewareSubscriber
{
    private string $env;

    public function __construct(ApplicationRepository $repository, $env)
    {
        parent::__construct($repository);
        $this->env = $env;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onException',
        ];
    }

    public function onException(ExceptionEvent $event): void
    {
        if (!$this->isMiddleware($event->getRequest())) {
            return;
        }

        $exception = $event->getThrowable();

//        print_r($exception); die();

        $response = ['message' => 'Unspecified error'];

        if (\in_array($this->env, ['dev', 'test'])) {
            $response['message'] = $exception->getMessage();
            $response['exception'] = $this->throwableToArray($exception);
        }

        if ($exception instanceof HttpException) {
            $response['message'] = $exception->getMessage();

            $this->setJsonResponse($event, $response);

            return;
        }

        $this->setJsonResponse($event, $response);
    }
}
