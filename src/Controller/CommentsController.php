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
 * Class CommentsController
 * @package App\Controller
 */
class CommentsController extends AbstractController
{
    /**
     * @var ActionOnDbService
     */
    private ActionOnDbService $actionOnDb;

    /**
     * CommentsController constructor.
     * @param ActionOnDbService $actionOnDb
     */
    public function __construct
    (
        ActionOnDbService $actionOnDb
    )
    {
        $this->actionOnDb = $actionOnDb;
    }

    /**
     * Deleting a comment.
     * @Route("/{id}", name="comments_delete", methods={"POST"})
     * @param Request $request
     * @param Comments $comment
     * @return Response
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
