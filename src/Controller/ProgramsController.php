<?php

namespace App\Controller;

use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use App\Form\ProgramType;
use App\Form\SearchProgramFormType;
use App\Repository\ProgramRepository;
use App\Service\Slugify;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("/programs", name="program_")
 */
class ProgramsController extends AbstractController
{
    private $slugify;

    public function __construct(Slugify $slugify)
    {
        $this->slugify = $slugify;
    }

    /**
     * Show all rows from ProgramFixtures’s entity
     *
     * @Route("/", name="index")
     * @return Response A response instance
     */
    public function index(Request $request, ProgramRepository $programRepository): Response
    {
        $form = $this->createForm(SearchProgramFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $search = $form->getData()['search'];
            $programs = $programRepository->findLikeName($search);
        } else {
            $programs = $programRepository->findAll();
        }
        return $this->render(
            'programs/index.html.twig',
            ['programs' => $programs,
                'form' => $form->createView(),]
        );
    }

    /**
     * @Route ("/new", name="new")
     * @param Request $request
     * @param Slugify $slugify
     * @param MailerInterface $mailer
     * @return Response
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */

    public function new(Request $request, Slugify $slugify, MailerInterface $mailer): Response
    {

        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Deal with the submitted data
            // For example : persist & flush the entity
            $entityManager = $this->getDoctrine()->getManager();

            $slug = $slugify->generate($program->getTitle());
            $program->setSlug($slug);

            $program->setOwner($this->getUser());

            $entityManager->persist($program);
            $entityManager->flush();
            $email = (new Email())
                ->from($this->getParameter('mailer_from'))
                ->to('your_email@example.com')
                ->subject('Une nouvelle série vient d\'être publiée !')
                ->html($this->renderView('programs/newProgramEmail.html.twig', ['program' => $program]));
            $mailer->send($email);

            $this->addFlash('success', 'The new program has been created');
            // And redirect to a route that display the result
            return $this->redirectToRoute('program_index');
        }
        return $this->render(
            'programs/new.html.twig',
            [
                "form" => $form->createView(),
            ]
        );

    }

    /**
     * @Route("/{slug}/edit", name="edit", methods={"GET","POST"})
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"slug": "slug"}})
     */
    public function edit(Request $request, Program $program, Slugify $slugify): Response
    {
        if (!($this->getUser() == $program->getOwner()) && !in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
            // If not the owner, throws a 403 Access Denied exception
            throw new AccessDeniedException('Only the owner can edit the program!');
        }

        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugify->generate($program->getTitle());
            $program->setSlug($slug);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('program_index');
        }

        return $this->render('programs/edit.html.twig', [
            'program' => $program,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Getting a program by id
     *
     * @Route("/{program_id}", name="show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"program_id": "slug"}})
     * @param int $id
     * @return Response
     */
    public function show(Program $program): Response
    {
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
                ->getRepository(ProgramFixtures::class)
                ->findOneBy(['id' => $programId]);*/

        /* $season = $this->getDoctrine()
             ->getRepository(SeasonFixtures::class)
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

    /**
     * @Route("/{slug}", name="delete", methods={"POST"})
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"slug": "id"}})
     * @IsGranted("ROLE_ADMIN")
     */

    public function delete(Request $request, Program $program): Response
    {
        if ($this->isCsrfTokenValid('delete' . $program->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($program);
            $entityManager->flush();
        }
        $this->addFlash('warning', 'A Program has been deleted');

        return $this->redirectToRoute('program_index');
    }

}
