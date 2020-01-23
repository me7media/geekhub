<?php

namespace App\CatalogBundle\Controller;

use App\CatalogBundle\Entity\Item;
use App\CatalogBundle\Form\ItemType;
use App\CatalogBundle\Repository\ItemRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/item")
 */
class ItemController extends AbstractController
{
    /**
     * @Route("/", name="item_index", methods={"GET"})
     * @Template()
     * @param ItemRepository $itemRepository
     * @return array
     */
    public function index(ItemRepository $itemRepository)
    {
        return [
            'items' => $itemRepository->findAll(),
        ];
    }

    /**
     * @Route("/new", name="item_new", methods={"GET","POST"})
     * @Template()
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function new(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $item = new Item();
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $item->setCreatedAt(new \DateTime('now'));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($item);
            $entityManager->flush();

            return $this->redirectToRoute('item_index');
        }

        return [
            'item' => $item,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/{id}", name="item_show", methods={"GET"})
     * @Template()
     * @param Item $item
     * @return array
     */
    public function show(Item $item)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('view', $item);

        return [
            'item' => $item,
        ];
    }

    /**
     * @Route("/{id}/edit", name="item_edit", methods={"GET","POST"})
     * @Template()
     * @param Request $request
     * @param Item $item
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function edit(Request $request, Item $item)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('edit', $item);

        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('item_index');
        }

        return [
            'item' => $item,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/{id}", name="item_delete", methods={"DELETE"})
     * @Template()
     * @param Request $request
     * @param Item $item
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Request $request, Item $item)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if ($this->isCsrfTokenValid('delete' . $item->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($item);
            $entityManager->flush();
        }

        return $this->redirectToRoute('item_index');
    }
}
