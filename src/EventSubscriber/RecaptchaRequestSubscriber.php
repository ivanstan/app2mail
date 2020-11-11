<?php

namespace App\EventSubscriber;

use App\Controller\DefaultController;
use App\Repository\ApplicationRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RecaptchaRequestSubscriber implements EventSubscriberInterface
{
    protected ApplicationRepository $repository;

    public function __construct(ApplicationRepository $repository)
    {
        $this->repository = $repository;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.request' => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        if ($request->attributes->get('_controller') !== DefaultController::class . '::application') {
            return;
        }

        $uuid = $request->attributes->get('uuid');

        $application = $this->repository->find($uuid);

        if (!$application) {
            throw new NotFoundHttpException(\sprintf('Application %s not found.', $uuid));
        }

        $recaptcha = $application->getRecaptcha();

        if ($recaptcha === null) {
            return;
        }

        $service = new \ReCaptcha\ReCaptcha($recaptcha->getSecretKey());

        $response = $service
            ->setExpectedHostname($request->headers->get('referer'))
            ->verify($request->request->get('recaptcha'), $request->getClientIp());

        if (!$response->isSuccess()) {
            $error = $response->getErrorCodes();
            throw new AccessDeniedHttpException(\sprintf('Recaptcha error occurred during validation: %s', $error[0]));
        }
    }
}
