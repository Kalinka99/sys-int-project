<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Form\CategoriesType;
use App\Repository\CategoriesRepository;
use App\Service\ActionOnDbService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 * @Route("/categories")
 * Class CategoriesController
 * @package App\Controller
 */
class CategoriesController extends AbstractController
{
    /**
     * @var CategoriesRepository
     */
    private CategoriesRepository $categoriesRepository;
    /**
     * @var ActionOnDbService
     */
    private ActionOnDbService $actionOnDb;

    /**
     * CategoriesController constructor.
     * @param CategoriesRepository $categoriesRepository
     * @param ActionOnDbService $actionOnDb
     */
    public function __construct
    (
        CategoriesRepository $categoriesRepository,
        ActionOnDbService $actionOnDb
    )
    {
        $this->categoriesRepository = $categoriesRepository;
        $this->actionOnDb = $actionOnDb;
    }

    /**
     * Shows a list of categories.
     * @Route("/", name="categories_index", methods={"GET"})
     * @return Response
     */
    public function index(): Response
    {
        $categories = $this->categoriesRepository->findAll();

        return $this->render('categories/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * New category form.
     * @Route("/new", name="categories_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $category = new Categories();
        $form = $this->createForm(CategoriesType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->actionOnDb
                ->addElement($category)
                ->executeUpdateOnDatabase();

            return $this->redirectToRoute('categories_index');
        }

        return $this->render('categories/new.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Shows a category and a list of articles with this category.
     * @Route("/{id}", name="categories_show", methods={"GET"})
     * @param Categories $category
     * @return Response
     */
    public function show(Categories $category): Response
    {
        $category = $this->categoriesRepository->findOneById($category);

        if (!$category) {
            $articles = [];
            $category = [];
        } else {
            $articles = $category->getArticles();
        }

        $allCategories = $this->categoriesRepository->findAll();

        return $this->render('categories/show.html.twig', [
            'articles' => $articles,
            'category' => $category,
            'categories' => $allCategories,
        ]);
    }

    /**
     * Editing the category - form.
     * @Route("/{id}/edit", name="categories_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Categories $category
     * @return Response
     */
    public function edit(Request $request, Categories $category): Response
    {
        $form = $this->createForm(CategoriesType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->actionOnDb
                ->addElement($category)
                ->executeUpdateOnDatabase();

            return $this->redirectToRoute('categories_index');
        }

        return $this->render('categories/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deleting a category.
     * @Route("/{id}", name="categories_delete", methods={"POST"})
     * @param Request $request
     * @param Categories $category
     * @return Response
     */
    public function delete(Request $request, Categories $category): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {

            if(!empty($category->getArticles())){
                foreach ($category->getArticles() as $article){
                    $article->setUsers(null);
                    $article->setCategories(null);

                    if(!empty($article->getComments() )){
                        foreach ($article->getComments() as $comment){
                            $article->removeComment($comment);
                            $this->actionOnDb->removeElement($comment);
                        }
                    }

                    if(!empty($article->getTags() )){
                        foreach ($article->getTags() as $tag){
                            $article->removeTag($tag);
                        }
                    }

                    $this->actionOnDb->removeElement($article);
                }
            }

            $this->actionOnDb
                ->removeElement($category)
                ->executeUpdateOnDatabase();
        }

        return $this->redirectToRoute('categories_index');
    }
}
