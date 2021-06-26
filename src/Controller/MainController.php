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
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->redirectToRoute('articles_index');
    }
    /**
     * @Route("/about", name="about")
     */
    public function about(): Response
    {
        $users = $this->usersRepository->findAll();

        return $this->render('main/about.html.twig', [
            'users' => $users
        ]);
    }
    /**
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

?>



