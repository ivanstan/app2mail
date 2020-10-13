<?php

namespace App\Controller;

use App\Entity\Submission;
use App\Event\SubmissionEvent;
use App\EventSubscriber\MailNotificationSubscriber;
use App\Repository\FormRepository;
use App\Repository\SubmissionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    public function submission(string $id, SubmissionRepository $repository): Response
    {
        $submission = $repository->find($id);

        if (!$submission) {
            throw new NotFoundHttpException(\sprintf('Unable to find Submission entity %s', $id));
        }

        return $this->render(MailNotificationSubscriber::TEMPLATE, ['submission' => $submission]);
    }

    /**
     * Accepts post data from form.
     * Special post fields:
     *  _replyTo: will be used as replayTo address in email sent to the address specified in Form::email list
     *  _redirect: if specified user will be redirected to it otherwise referer header will be used.
     *             Either specify _redirect or use <meta name="referrer" content="origin">.
     *
     * @Route("/form/{uuid}", name="form", methods={"POST"})
     */
    public function form(Request $request, string $uuid, FormRepository $repository, EventDispatcherInterface $dispatcher): RedirectResponse
    {
        $form = $repository->find($uuid);

        if (!$form) {
            throw new NotFoundHttpException(\sprintf('Form %s not found.', $uuid));
        }

        $data = array_map('trim', $request->request->all());

        $redirect = $data['_redirect'] ?? $request->headers->get('referer');
        unset($data['_redirect']);

        $submission = new Submission();
        $submission->setForm($form);
        $submission->setData($data);

        $repository->save($submission);

        $dispatcher->dispatch(new SubmissionEvent($submission), SubmissionEvent::NAME);

        return $this->redirect($redirect);
    }
}
