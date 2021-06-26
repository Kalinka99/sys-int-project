<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\UsersType;
use App\Repository\UsersRepository;
use App\Service\ActionOnDbService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/users")
 */
class UsersController extends AbstractController
{
    private UsersRepository $usersRepository;

    private ActionOnDbService $actionOnDb;

    public function __construct
    (
        UsersRepository $usersRepository,
        ActionOnDbService $actionOnDb
    )
    {
        $this->usersRepository = $usersRepository;
        $this->actionOnDb = $actionOnDb;
    }

    /**
     * @Route("/", name="users_index", methods={"GET"})
     */
    public function index(): Response
    {
        $users = $this->usersRepository->findAll();

        return $this->render('users/index.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/{id}/edit", name="users_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Users $user): Response
    {
        $form = $this->createForm(UsersType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!empty($form->getData()['password'])) {
                $user->setPassword(password_hash($form->getData()['password'], PASSWORD_BCRYPT));
            }

            if (!is_null($form->getData()['email']) && strcmp($form->getData()['email'], $user->getEmail()) !== 0){
                $user->setEmail($form->getData()['email']);
            }

            if (!is_null($form->getData()['about']) && strcmp($form->getData()['about'], $user->getAbout()) !== 0){
                $user->setAbout($form->getData()['about']);
            }

            if (!is_null($form->getData()['contact']) && strcmp($form->getData()['contact'], $user->getContact()) !== 0){
                $user->setContact($form->getData()['contact']);
            }

            $this->actionOnDb
                ->addElement($user)
                ->executeUpdateOnDatabase();

            return $this->redirectToRoute('users_index');
        }

        return $this->render('users/edit.html.twig', [
            'user' => $user,
            'email'=> $user->getEmail(),
            'form' => $form->createView(),
        ]);
    }
}
?>