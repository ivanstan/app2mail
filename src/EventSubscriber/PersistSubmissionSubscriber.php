<?php


namespace App\EventSubscriber;


use App\Event\SubmissionEvent;
use App\Repository\ApplicationRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PersistSubmissionSubscriber implements EventSubscriberInterface
{
    protected ApplicationRepository $repository;

    public function __construct(ApplicationRepository $repository)
    {
        $this->repository = $repository;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            SubmissionEvent::NAME => 'onSubmission',
        ];
    }

    public function onSubmission(SubmissionEvent $event): void
    {
        $this->repository->save($event->getSubmission());
    }
}