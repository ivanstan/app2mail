<?php

namespace App\Controller;

use App\Entity\Application;
use App\Entity\Submission;
use App\Event\SubmissionEvent;
use App\EventSubscriber\MailNotificationSubscriber;
use App\Repository\SubmissionRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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
     * Accepts post data from application.
     * Special post fields:
     *  _replyTo: will be used as replayTo address in email sent to the address specified in Application::email list
     *  _redirect: if specified user will be redirected to it otherwise referer header will be used.
     *             Either specify _redirect or use <meta name="referrer" content="origin">.
     *
     * @Route("/application/{uuid}", name="application", methods={"POST"})
     * @Entity("application", expr="repository.find(uuid)")
     */
    public function application(Request $request, Application $application, EventDispatcherInterface $dispatcher): RedirectResponse
    {
        $data = array_map('trim', $request->request->all());

        $redirect = $data['_redirect'] ?? $request->headers->get('referer');
        unset($data['_redirect']);

        $submission = new Submission($application);
        $submission->setData($data);

        $dispatcher->dispatch(new SubmissionEvent($submission), SubmissionEvent::NAME);

        $response = $this->redirect($redirect);
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }
}
