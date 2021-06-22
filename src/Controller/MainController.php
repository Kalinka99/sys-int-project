<?php

namespace App\Controller;

use App\Repository\ArticlesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Articles as Article;
use App\Entity\Categories as Category;
use App\Entity\Comments as Comment;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;


class MainController extends AbstractController
{
    /**
     * @Route(
     *     "/",
     *     name="index",
     * )
     */
    public function index(): Response
    {
        return $this->redirectToRoute('articles_index');
    }
    /** @Route(
     *     "/about",
     *     name="about",
     * )
     */
    public function about(): Response
    {
        return $this->render(
            'main/about.html.twig');
    }
    /** @Route(
     *     "/contact",
     *     name="contact"
     * )
     */
    public function contact(): Response
    {
        return $this->render(
            'main/contact.html.twig');
    }
    /** @Route(
     *     "/read", name="read"
     * )
     */
    public function read(): Response
    {
        /** @var Article[] $articles */
        /** @var Comment[] $comments */
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();
        $comments = $this->getDoctrine()->getRepository(Comment::class)->findAll();
        return $this->render('main/read.html.twig', [
            'articles' => $articles,
            'comments'=>$comments
        ]);
    }

}






