<?php

namespace App\Controller;

use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Episode;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ProgramType;

/**
 * @Route("/programs", name="program_")
 */
Class ProgramsController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @return Response A response instance
     */
    public function index(): Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();
        return $this->render(
            'programs/index.html.twig',
            ['programs' => $programs]
        );
    }
    /**
     * The controller for the program add form
     *
     * @Route("/new", name="new")
     */
    public function new(Request $request): Response
    {
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);
        if ($form ->isSubmitted()){
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($program);

            $entityManager->flush();

            return $this->redirectToRoute('program_index');
        }

        return $this->render('programs/new.html.twig', ["form" => $form->createView(),]);

    }

    /**
     * Getting a program by id
     *
     * @Route("/show/{id<^[0-9]+$>}", name="show")
     */
    public function show(Program $program): Response
    {
        $seasons = $program->getSeasons();
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : '.$program.'found in programs table.'
            );
        }
        return $this->render('programs/show.html.twig',
            [
                'program'=> $program,
                'seasons' => $seasons
            ]);
    }
    /**
     * @Route("/programs/{programId}/season/{seasonId}", name="season_show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"programId": "id"}})
     * @ParamConverter("season", class="App\Entity\Season", options={"mapping": {"seasonId": "id"}})
     */
    public function showSeason(Program $program, Season $season): Response
    {
        $episodes = $season->getEpisodes();
        return $this->render('programs/season_show.html.twig', [
            'season' => $season,
            'program' => $program,
            'episodes' => $episodes
        ]);
    }
    /**
     * @Route("/program/{programId}/seasons/{seasonId}/episodes/{episodeId}", name="episode_show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"programId": "id"}})
     * @ParamConverter("season", class="App\Entity\Season", options={"mapping": {"seasonId": "id"}})
     * @ParamConverter("episode", class="App\Entity\Episode", options={"mapping": {"episodeId": "id"}})
     */
    public function showEpisode(Program $program, Season $season, Episode $episode): Response
    {
        return $this->render('programs/episode_show.html.twig', [
            'season' => $season,
            'program' => $program,
            'episode' => $episode
        ]);
    }
}