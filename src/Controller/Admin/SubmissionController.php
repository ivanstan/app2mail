<?php

namespace App\Controller\Admin;

use App\Entity\Submission;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/submission')]
class SubmissionController extends AbstractController
{
    #[Route('/{submission}/delete', name: "submission_delete", methods: ["POST"])]
    public function delete(Submission $submission, Request $request): RedirectResponse {
        $uuid = $submission->getApplication()->getUuid();

        if ($this->isCsrfTokenValid('delete_'.$submission->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($submission);
            $em->flush();
        }

        return $this->redirectToRoute('application_show', ['uuid' => $uuid]);
    }
}
