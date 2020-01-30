<?php

namespace App\CatalogBundle\Controller;

use App\CatalogBundle\Entity\Comment;
use App\CatalogBundle\Entity\Item;
use App\CatalogBundle\Form\CommentType;
use App\CatalogBundle\Form\ItemCommentType;
use App\CatalogBundle\Repository\CommentRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/comment")
 */
class CommentController extends AbstractController
{
    /**
     * @Route("/", name="comment_index", methods={"GET"})
     * @Template()
     * @param CommentRepository $commentRepository
     * @return array
     */
    public function index(CommentRepository $commentRepository)
    {
        return [
            'comments' => $commentRepository->findAll(),
        ];
    }

    /**
     * @Route("/new", name="comment_new", methods={"GET","POST"})
     * @Template()
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function new(Request $request, ?Item $item)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $comment = new Comment();

            $form = $this->createForm(CommentType::class, $comment);
        if($item){
            $comment->setItem($item);
            $form = $this->createForm(ItemCommentType::class, $comment, [
                'action' => $this->generateUrl('comment_new'),
            ]);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $comment->setCreatedAt(new \DateTime('now'));
            $comment->setAuthor($this->getUser());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            if($item){
                return $this->redirectToRoute('item_show', ['item' => $item]);
            }

            return $this->redirectToRoute('comment_index');
        }

        return [
            'comment' => $comment,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/{id}", name="comment_show", methods={"GET"})
     * @Template()
     * @param Comment $comment
     * @return array
     */
    public function show(Comment $comment)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return [
            'comment' => $comment,
        ];
    }

    /**
     * @Route("/{id}/edit", name="comment_edit", methods={"GET","POST"})
     * @Template()
     * @param Request $request
     * @param Comment $comment
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function edit(Request $request, Comment $comment)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('edit', $comment);

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('comment_index');
        }

        return [
            'comment' => $comment,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/{id}", name="comment_delete", methods={"DELETE"})
     * @Template()
     * @param Request $request
     * @param Comment $comment
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Request $request, Comment $comment)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if ($this->isCsrfTokenValid('delete' . $comment->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($comment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('comment_index');
    }
}
