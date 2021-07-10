<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Kalina Muchowicz <https://github.com/Kalinka99>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace App\Controller;

use App\Entity\Comments;
use App\Service\CommentsService;
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
     * Comments Service.
     * @var CommentsService
     */
    private CommentsService $commentsService;

    /**
     * CommentsController constructor.
     * @param CommentsService $commentsService
     */
    public function __construct(CommentsService $commentsService)
    {
        $this->commentsService = $commentsService;
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
            $this->commentsService
                ->removeComment($comment)
                ->executeUpdateOnDatabase();
        }

        return $this->redirectToRoute('articles_show', [
            'id' => $articleId
        ]);
    }
}
