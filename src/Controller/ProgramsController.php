<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/programs", name="program_")
 */

class ProgramsController extends AbstractController
{
    /**
     * @Route("/", name="program_index")
     */
    public function index(): Response
    {
        return $this->render('programs/index.html.twig', [
            'website' => 'Wild Series',
        ]);
    }

    /**
     * @Route("/{id}", requirements={"id"="\d+"}, name="show", methods={"GET"} )
     */
    public function show(int $id): Response
    {
        return $this->render('programs/show.html.twig', ['id' => 4]);

    }


}
