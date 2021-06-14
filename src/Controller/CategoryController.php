<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Program;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/categories", name="category_")
 */
class CategoryController extends AbstractController
{
    /**
     * Show all rows from Category’s entity
     *
     * @Route("/", name="index")
     * @return Response A response instance
     */
    public function index(): Response
    {
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        return $this->render(
            'category/index.html.twig',
            [
                'categories' => $categories
            ]
        );
    }

    /**
     * @Route ("/new", name="new")
     * @IsGranted("ROLE_ADMIN")
     * @param Request $request
     * @return Response
     */

    public function new(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            // Deal with the submitted data
            // For example : persiste & flush the entity
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();
            // And redirect to a route that display the result
            return $this->redirectToRoute('category_index');
        }

        return $this->render(
            'category/new.html.twig',
            [
                "form" => $form->createView(),
            ]
        );
    }

    /**
     * Show all products from Category’s entity
     *
     * @Route("/{categoryName}", name="_show")
     * @param string $categoryName
     * @return Response A response instance
     */

    public function show(string $categoryName): Response
    {
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(['name' => $categoryName]);

        if (!$category) {
            throw $this->createNotFoundException(
                'No category with name : ' . $categoryName . ' found in category\'s table.'
            );
        }

        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findBy(['category' => $category], ['id' => 'DESC'], 3);


        return $this->render(
            'category/show.html.twig',
            [
                'category' => $category,
                'programs' => $programs
            ]
        );
    }
}
