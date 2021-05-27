<?php

namespace App\Controller;

use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use App\Repository\ProgramRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/programs", name="program_")
 */
class ProgramsController extends AbstractController
{
    /**
     * Show all rows from Program’s entity
     *
     * @Route("/", name="index")
     * @return Response A response instance
     */
    public function index(ProgramRepository $programRepository): Response
    {
        return $this->render(
            'programs/index.html.twig',
            ['programs' => $programRepository->findAll()]
        );
    }

    /**
     * Getting a program by id
     *
     * @Route("/{program_id}", name="show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"program_id": "id"}})
     * @param int $id
     * @return Response
     */
    public function show(Program $program): Response
    {
        /*if (!$program) {
              throw $this->createNotFoundException(
                  'No program with id : ' . $id . ' found in program\'s table.'
              );
          }*/

        $seasons = $this->getDoctrine()->getRepository(Season::class)
            ->findBy(['program' => $program]);

        return $this->render(
            'programs/show.html.twig',
            [
                'program' => $program,
                'seasons' => $seasons
            ]
        );
    }

    /**
     * @Route("/{programId<^[0-9]+$>}/seasons/{seasonId<^[0-9]+$>}",  name="season_show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"programId": "id"}})
     * @ParamConverter("season", class="App\Entity\Season", options={"mapping": {"seasonId": "id"}})
     */

    public function showSeason(Program $program, Season $season): Response
    {
        /*    $program = $this->getDoctrine()
                ->getRepository(Program::class)
                ->findOneBy(['id' => $programId]);*/

        /* $season = $this->getDoctrine()
             ->getRepository(Season::class)
             ->findOneBy(['id' => $seasonId]);*/

        $episodes = $this->getDoctrine()
            ->getRepository(Episode::class)
            ->findBy(['season' => $season]);

        return $this->render(
            'programs/season_show.html.twig',
            [
                'season' => $season,
                'program' => $program,
                'episodes' => $episodes
            ]

        );
    }

    /**
     * @Route("/{programId<^[0-9]+$>}/seasons/{seasonId<^[0-9]+$>}/episodes/{episodeId<^[0-9]+$>}",  name="episode_show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"programId": "id"}})
     * @ParamConverter("season", class="App\Entity\Season", options={"mapping": {"seasonId": "id"}})
     * @ParamConverter("episode", class="App\Entity\Episode", options={"mapping": {"episodeId": "id"}})
     */

    public function showEpisode(Program $program, Season $season, Episode $episode): Response
    {
        return $this->render(
            'programs/episode_show.html.twig',
            [
                'program' => $program,
                'season' => $season,
                'episode' => $episode
            ]
        );
    }


}
