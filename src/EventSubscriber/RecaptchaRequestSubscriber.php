<?php

namespace App\EventSubscriber;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class RecaptchaRequestSubscriber extends ApplicationMiddlewareSubscriber
{
    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.request' => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        if (!$this->isMiddleware($request)) {
            return;
        }

        $recaptcha = $this->getApplication($request)->getRecaptcha();

        if ($recaptcha === null) {
            return;
        }

        $service = new \ReCaptcha\ReCaptcha($recaptcha->getSecretKey());

        $response = $service
            ->setExpectedHostname($request->headers->get('referer'))
            ->verify($request->request->get('recaptcha'), $request->getClientIp());

//        if (!$response->isSuccess()) {
//            $error = $response->getErrorCodes();
//            $exception = new AccessDeniedHttpException(\sprintf('Recaptcha error occurred during validation: %s', $error[0]));
//
//            $this->setJsonResponse($event, $this->throwableToArray($exception));
//        }

        $request->request->remove('recaptcha');
    }
}
