<?php

namespace App\Controller;

use App\DataFixtures\ApplicationFixtures;
use App\Entity\Application;
use App\Entity\Submission;
use App\EventSubscriber\MailNotificationSubscriber;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/test", condition="'dev' === '%kernel.environment%'")
 */
class TestController extends AbstractController
{
    /**
     * @Route("/submission/{submission}", name="test_submission")
     * @Entity("submission", expr="repository.find(submission)")
     */
    public function submission(Submission $submission): Response
    {
        return $this->render(MailNotificationSubscriber::TEMPLATE, ['submission' => $submission]);
    }

    /**
     * @Route("/application", name="test_application")
     */
    public function application(): Response
    {
        $application = $this->getDoctrine()->getRepository(Application::class)->findBy(
            [
                'name' => ApplicationFixtures::APP_NAME,
            ]
        );

        if ($application === null) {
            throw new \RuntimeException(\sprintf('Unable to find application with name: %s', ApplicationFixtures::APP_NAME));
        }

        return $this->render('test/application.html.twig', ['application' => $application]);
    }
}