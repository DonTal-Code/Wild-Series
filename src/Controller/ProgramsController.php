<?php

namespace App\Controller;

use App\Entity\Program;
use App\Repository\ProgramRepository;
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
     * @Route("/show/{id<^[0-9]+$>}", name="show")
     * @param int $id
     * @return Response
     */
    public function show(int $id):Response
    {
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['id' => $id]);

        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : '.$id.' found in program\'s table.'
            );
        }
        return $this->render('programs/show.html.twig', [
            'program' => $program,
        ]);
    }



}
