<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\Categories;
use App\Entity\Comments;
use App\Form\CommentsType;
use App\Repository\CommentsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/comments")
 */
class CommentsController extends AbstractController
{
    /**
     * @Route("/{id}", name="comments_delete", methods={"POST"})
     */
    public function delete(Request $request, Comments $comment): Response
    {
        $articleId =$comment->getArticles()->getId();
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($comment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('articles_show', ['id' => $articleId]);
    }

}
