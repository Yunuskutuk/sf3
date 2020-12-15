<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProgramsController extends AbstractController
{
    /**
     * @Route("/programs/{id}", methods={"GET"}, name="program_show")
     */
    public function show(int $id): Response
    {
        return $this->render('programs/show.html.twig', ['id' => $id]);
    }
}