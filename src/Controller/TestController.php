<?php

namespace App\Controller;

use App\Entity\Submission;
use App\EventSubscriber\MailNotificationSubscriber;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;

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
}