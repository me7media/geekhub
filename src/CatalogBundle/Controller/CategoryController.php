<?php

namespace App\CatalogBundle\Controller;

use App\CatalogBundle\Entity\Category;
use App\CatalogBundle\Form\CategoryType;
use App\CatalogBundle\Repository\CategoryRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/category")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="category_index", methods={"GET"})
     * @Template()
     * @param CategoryRepository $categoryRepository
     * @return array
     */
    public function index(CategoryRepository $categoryRepository)
    {
        return [
            'categories' => $categoryRepository->findAll()
        ];
    }

    /**
     * @Route("/new", name="category_new", methods={"GET","POST"})
     * @Template()
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function new(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('category_index');
        }

        return [
            'category' => $category,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/{id}", name="category_show", methods={"GET"})
     * @Template()
     * @param Category $category
     * @return array
     */
    public function show(Category $category)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return [
            'category' => $category,
        ];
    }

    /**
     * @Route("/{id}/edit", name="category_edit", methods={"GET","POST"})
     * @Template()
     * @param Request $request
     * @param Category $category
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function edit(Request $request, Category $category)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('category_index');
        }

        return [
            'category' => $category,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/{id}", name="category_delete", methods={"DELETE"})
     * @param Request $request
     * @param Category $category
     * @return Response
     */
    public function delete(Request $request, Category $category): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        if ($this->isCsrfTokenValid('delete' . $category->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($category);
            $entityManager->flush();
        }

        return $this->redirectToRoute('category_index');
    }
}
