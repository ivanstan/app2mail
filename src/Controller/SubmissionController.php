<?php

namespace App\Controller;

use App\Entity\Submission;
use App\Form\SubmissionType;
use App\Repository\SubmissionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/submission")
 */
class SubmissionController extends AbstractController
{
    /**
     * @Route("/", name="submission_index", methods={"GET"})
     */
    public function index(SubmissionRepository $submissionRepository): Response
    {
        return $this->render('submission/index.html.twig', [
            'submissions' => $submissionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="submission_show", methods={"GET"})
     */
    public function show(Submission $submission): Response
    {
        return $this->render('submission/show.html.twig', [
            'submission' => $submission,
        ]);
    }

    /**
     * @Route("/new", name="submission_new", methods={"GET","POST"})
     * @Route("/{id}/edit", name="submission_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ?Submission $submission): Response
    {
        if ($submission === null) {
            $submission = new Submission();
        }

        $form = $this->createForm(SubmissionType::class, $submission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($submission->getId() === null) {
                $this->getDoctrine()->getManager()->persist($submission);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('submission_index');
        }

        return $this->render('submission/edit.html.twig', [
            'submission' => $submission,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="submission_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Submission $submission): Response
    {
        if ($this->isCsrfTokenValid('delete'.$submission->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($submission);
            $entityManager->flush();
        }

        return $this->redirectToRoute('submission_index');
    }
}
