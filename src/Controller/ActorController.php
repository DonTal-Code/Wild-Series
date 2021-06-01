<?php

namespace App\Controller;

use App\Entity\Actor;
use App\Entity\Program;
use App\Repository\ActorRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/actor", name="actor")
 */
class ActorController extends AbstractController
{
    /**
     * @Route("/", name="_home")
     */
    public function index(ActorRepository $actorRepository): Response
    {
        return $this->render(
            'actor/index.html.twig',
            ['actors' => $actorRepository->findAll()]
        );
    }


    /**
     * @Route("/{id<^[0-9]+$>}", name="_show", methods={"GET"})
     */
    public function show(int $id): Response
    {
        $program = $this->getDoctrine()->getRepository(Program::class)
            ->findOneBy(['id' => $id]);
        $actor = $this->getDoctrine()->getRepository(Actor::class)
            ->findOneBy(['id' => $id]);
        return $this->render('actor/show.html.twig', [
            'actor' => $actor,
            'program' => $program
        ]);
    }
}
