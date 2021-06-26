<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Service\ActionOnDbService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/comments")
 */
class CommentsController extends AbstractController
{
    private ActionOnDbService $actionOnDb;

    public function __construct
    (
        ActionOnDbService $actionOnDb
    )
    {
        $this->actionOnDb = $actionOnDb;
    }

    /**
     * @Route("/{id}", name="comments_delete", methods={"POST"})
     */
    public function delete(Request $request, Comments $comment): Response
    {
        $articleId = $comment->getArticles()->getId();

        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $this->actionOnDb
                ->removeElement($comment)
                ->executeUpdateOnDatabase();
        }

        return $this->redirectToRoute('articles_show', [
            'id' => $articleId
        ]);
    }

}
?>