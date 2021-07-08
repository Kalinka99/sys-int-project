<?php

namespace App\Controller;

use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    private UsersRepository $usersRepository;

    public function __construct
    (
        UsersRepository $usersRepository
    )
    {
        $this->usersRepository = $usersRepository;
    }

    /**
     * Redirection to articles index page.
     * @Route("/", name="index")
     * @return Response
     */
    public function index(): Response
    {
        return $this->redirectToRoute('articles_index');
    }

    /**
     * "About" page.
     * @Route("/about", name="about")
     * @return Response
     */
    public function about(): Response
    {
        $users = $this->usersRepository->findAll();

        return $this->render('main/about.html.twig', [
            'users' => $users
        ]);
    }
    /**
     * "Contact" page.
     * @Route("/contact", name="contact")
     */
    public function contact(): Response
    {
        $users = $this->usersRepository->findAll();

        return $this->render('main/contact.html.twig', [
            'users' => $users
        ]);
    }
}



