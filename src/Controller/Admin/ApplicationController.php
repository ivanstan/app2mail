<?php

namespace App\Controller\Admin;

use App\Entity\Application;
use App\Repository\ApplicationRepository;
use App\Repository\SubmissionRepository;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/application')]
final class ApplicationController extends AbstractController
{
    #[Route('/', name: "application_index", methods: ["GET"])]
    public function index(Request $request, ApplicationRepository $repository): Response
    {
        $pager = new Pagerfanta(new ArrayAdapter($repository->findAll()));
        $pager->setCurrentPage($request->query->get('page', 1));

        return $this->render(
            'admin/application/index.html.twig',
            [
                'pager' => $pager,
            ]
        );
    }

    #[Route('/{uuid}', name: "application_show", methods: ['GET'])]
    public function show(Request $request, Application $application, SubmissionRepository $repository): Response
    {
        $pager = new Pagerfanta(new ArrayAdapter($repository->findBy(['application' => $application->getUuid()])));
        $pager->setCurrentPage($request->query->get('page', 1));

        return $this->render(
            'admin/application/show.html.twig',
            [
                'application' => $application,
                'pager' => $pager,
            ]
        );
    }
}
