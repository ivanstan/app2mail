<?php

namespace App\EventSubscriber;

use App\Event\SubmissionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class MailNotificationSubscriber implements EventSubscriberInterface
{
    public const TEMPLATE = 'submission/preview.html.twig';

    protected MailerInterface $mailer;
    protected Environment $twig;
    protected string $mailFrom;

    public function __construct(MailerInterface $mailer, Environment $twig, $mailFrom)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->mailFrom = $mailFrom;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            SubmissionEvent::NAME => 'onSubmission',
        ];
    }

    public function onSubmission(SubmissionEvent $event): void
    {
        $submission = $event->getSubmission();
        $data = $submission->getData();

        foreach ($submission->getApplication()->getEmail() as $email) {
            $email = (new Email())
                ->from($this->mailFrom)
                ->to($email)
                ->priority(Email::PRIORITY_HIGH)
                ->subject(\sprintf('New application submission | %s', $submission->getApplication()->getName()))
                ->html($this->twig->render(self::TEMPLATE, ['submission' => $submission]));

            if (isset($data['_replyTo']) && filter_var($data['_replyTo'], FILTER_VALIDATE_EMAIL)) {
                $email->replyTo($data['_replyTo']);
            }

            $this->mailer->send($email);
        }
    }
}
