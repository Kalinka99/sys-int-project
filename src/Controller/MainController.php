<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


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
        $doctrine = $this->getDoctrine();
        $users = $doctrine->getRepository(Users::class)->findAll();
        return $this->render(
            'main/about.html.twig', [
                'users' => $users
        ]);
    }
    /** @Route(
     *     "/contact",
     *     name="contact"
     * )
     */
    public function contact(): Response
    {
        $doctrine = $this->getDoctrine();
        $users = $doctrine->getRepository(Users::class)->findAll();
        return $this->render(
            'main/contact.html.twig', [
                'users' => $users
        ]);
    }
}






