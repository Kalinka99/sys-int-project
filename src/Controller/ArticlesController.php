<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\Categories;
use App\Form\ArticlesType;
use App\Repository\ArticlesRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/articles")
 */
class ArticlesController extends AbstractController
{
    /**
     * @var ArticlesRepository
     */
    private $articleRepository;

    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * @param ArticlesRepository  $articleRepository
     * @param PaginatorInterface $paginator
     */
    public function __construct(
        ArticlesRepository $articleRepository,
        PaginatorInterface $paginator
    ) {
        $this->articleRepository = $articleRepository;
        $this->paginator = $paginator;
    }

    /**
     * @Route("/articles/list/{page}", methods={"GET"})
     */
    public function list(string $page): Response
    {
        $articles = $this->articleRepository->findAll();
        $articles = $this->paginator->paginate($articles, $page, ArticlesRepository::PAGE_LIMIT);

        return $this->render(
            'articles/index.html.twig',
            ['articles' => $articles]
        );
    }


    /**
     * @Route("/", name="articles_index", methods={"GET"})
     */
    public function index(ArticlesRepository $articlesRepository): Response
    {

        return $this->render('articles/index.html.twig', [
            'articles' => $articlesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="articles_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $doctrine = $this->getDoctrine();
        $categories = $doctrine->getRepository(Categories::class)->findAll();
        $options = [
            'categories' => $this->serializeCategories($categories)
        ];

        $form = $this->createForm(ArticlesType::class, null, $options);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $data = $form->getData();
            $article = (new Articles())
                ->setTitle($data['title'])
                ->setMainText($data['mainText'])
                ->setCreated(new \DateTime('now'))
                ->setCategories($doctrine->getRepository(Categories::class)->findOneById($data['categories']))
                ->setUsers($this->getUser());
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('articles_index');
        }

        return $this->render('articles/new.html.twig', [
//            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="articles_show", methods={"GET"})
     */
    public function show(Articles $article): Response
    {
        return $this->render('articles/show.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="articles_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Articles $article): Response
    {
        $doctrine = $this->getDoctrine();
        $categories = $doctrine->getRepository(Categories::class)->findAll();
        $options = [
            'categories' => $this->serializeCategories($categories)
        ];
        $form = $this->createForm(ArticlesType::class, null, $options);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $data = $form->getData();
            $article
                ->setTitle($data['title'])
                ->setMainText($data['mainText'])
                ->setCategories($doctrine->getRepository(Categories::class)->findOneById($data['categories']));
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('articles_index');
        }
        return $this->render('articles/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="articles_delete", methods={"POST"})
     */
    public function delete(Request $request, Articles $article): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('articles_index');
    }
    private function serializeCategories($categories){
        $response = [];
        /** @var Categories $category */
        foreach ($categories as $category){
            $response[$category->getName()] = $category->getId();
        }
        return $response;
    }
}
