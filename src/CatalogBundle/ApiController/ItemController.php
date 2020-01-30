<?php

namespace App\CatalogBundle\ApiController;

use App\CatalogBundle\Entity\Category;
use App\CatalogBundle\Entity\Item;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/item")
 */
class ItemController extends AbstractController
{
    /**
     * @Route("/new", name="item_new", methods={"POST"})
     * @param Request $request
     * @return array
     */
    public function new(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $entityManager = $this->getDoctrine()->getManager();
        $item = new Item();
        $request = $request->request->get('item', []);

        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy($request['category']);

        if(!$category){
            $category = new Category();
            $category->setTitle($request['category']);

            $entityManager->persist($category);
        }

        $item->setCategory($category);
        $item->setTitle($request['title']);
        $item->setName($request['name']);
        $item->setText($request['text']);
        $item->setLink($request['link']);

            $item->setCreatedAt(new \DateTime('now'));
            $item->setAuthor($this->getUser());

            $entityManager->persist($item);
            $entityManager->flush();

        return [
            'item' => $item,
        ];
    }
}