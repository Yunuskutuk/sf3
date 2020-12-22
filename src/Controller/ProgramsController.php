<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Program;

class ProgramsController extends AbstractController
{
    /**
     * @Route("/programs/{id}", methods={"GET"}, name="program_show")
     */
    public function show(int $id):Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['id' => $id]);

        if (!$programs) {
            throw $this->createNotFoundException(
                'No program with id : '.$id.' found in program\'s table.'
            );
        }
        return $this->render('programs/show.html.twig', [
            'programs' => $programs,
        ]);
    }

    /**
     * Show all rows from Program’s entity
     *
     * @Route("/programs", name="program_index")
     * @return Response A response instance
     */
    public function index(): Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();

        return $this->render('programs/index.html.twig', ['programs' => $programs]);
    }
}