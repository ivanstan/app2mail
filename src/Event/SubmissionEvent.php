<?php

namespace App\Event;

use App\Entity\Submission;
use Symfony\Contracts\EventDispatcher\Event;

class SubmissionEvent extends Event
{
    public const NAME = 'onSubmission';

    protected Submission $submission;

    public function __construct(Submission $submission)
    {
        $this->submission = $submission;
    }

    public function getSubmission(): Submission
    {
        return $this->submission;
    }

    public function setSubmission(Submission $submission): void
    {
        $this->submission = $submission;
    }
}