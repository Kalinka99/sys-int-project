<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\Categories;
use App\Entity\Comments;
use App\Entity\Tags;
use App\Form\ArticlesType;
use App\Form\CommentsType;
use App\Repository\ArticlesRepository;
use App\Repository\CategoriesRepository;
use App\Repository\CommentsRepository;
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
     * @Route("/{page<\d+>?1}", name="articles_index", methods={"GET"})
     */
    public function index(int $page, CategoriesRepository $categoriesRepository): Response
    {

        $articles = $this->articleRepository->findBy([], ['id'=>'DESC']);
        $articles = $this->paginator->paginate($articles, $page,    10);

        return $this->render('articles/index.html.twig', [
            'articles' => $articles,
            'categories' => $categoriesRepository->findAll(),

        ]);
    }


    /**
     * @Route("/new", name="articles_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $doctrine = $this->getDoctrine();
        $categories = $doctrine->getRepository(Categories::class)->findAll();
        $tags = $doctrine->getRepository(Tags::class)->findAll();
        $options = [
            'categories' => $this->serializeObject($categories),
            'tags' => $this->serializeObject($tags),
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
                ->setUsers($this->getUser())
                ->addTag($doctrine->getRepository(Tags::class)->findOneById($data['tags']));
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
     * @Route("/show/{id}", name="articles_show", methods={"GET", "POST"})
     */
    public function show(Articles $article, Request $request): Response
    {
        $doctrine = $this->getDoctrine();
        $form = $this->createForm(CommentsType::class);
        if($request->getMethod()==='POST'){
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $doctrine->getManager();

                $data = $form->getData();
                $comment = (new Comments())
                    ->setAuthorUsername($data['authorUsername'])
                    ->setAuthorEmail($data['authorEmail'])
                    ->setMainText($data['mainText'])
                    ->setCreated(new \DateTime('now'))
                    ->setArticles($article);

                $entityManager->persist($comment);
                $entityManager->flush();
            }
        }



        return $this->render('articles/show.html.twig', [
            'article' => $article,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="articles_edit_1", methods={"GET","POST"})
     */
    public function edit(Request $request, Articles $article): Response
    {
        $doctrine = $this->getDoctrine();
        $categories = $doctrine->getRepository(Categories::class)->findAll();
        $options = [
            'categories' => $this->serializeObject($categories),
            'removeTags' => true,
            'tagsToRemove' => $this->serializeObject($article->getTags())
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
//                ->addTag($doctrine->getRepository(Tags::class)->findOneById($data['tags']));
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
     * @Route("/by-category/{page<\d+>?1}/{categoryId}", name="articles_by_category", methods={"GET"})
     * @param int $page
     * @param int $categoryId
     * @return Response
     */
    public function articlesCategory(int $page, int $categoryId): Response
    {

        $category = $this->getDoctrine()->getRepository(Categories::class)->findOneById($categoryId);
        $articles = $category->getArticles();
        $articles = $this->paginator->paginate($articles, $page,    10);

        return $this->render('articles/index.html.twig', [
            'articles' => $articles,
            'categories' => $this->getDoctrine()->getRepository(Categories::class)->findAll(),

        ]);
    }


    /**
     * @Route("/by-tag/{page<\d+>?1}/{tagId}", name="articles_by_tags", methods={"GET"})
     * @param int $page
     * @param int $tagId
     * @return Response
     */
    public function articlesTag(int $page, int $tagId): Response
    {

        $tag = $this->getDoctrine()->getRepository(Tags::class)->findOneById($tagId);
        $articles = $tag->getArticles();
        $articles = $this->paginator->paginate($articles, $page,    10);

        return $this->render('articles/index.html.twig', [
            'articles' => $articles,
            'tags' => $this->getDoctrine()->getRepository(Tags::class)->findAll(),
            'categories' => $this->getDoctrine()->getRepository(Categories::class)->findAll()

        ]);
    }


    /**
     * @Route("/{id}", name="articles_delete", methods={"POST"})
     */
    public function delete(Request $request, Articles $article): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $article->setUsers(null);
            $article->setCategories(null);
            if(!empty($article->getComments() )){
                foreach ($article->getComments() as $comment){
                    $article->removeComment($comment);
                    $entityManager->remove($comment);
                }
            }
            if(!empty($article->getTags() )){
                foreach ($article->getTags() as $tag){
                    $article->removeTag($tag);
                }
            }

            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('articles_index');
    }
    private function serializeObject($objects){
        $response = [];
        foreach ($objects as $object){
            $response[$object->getName()] = $object->getId();
        }
        return $response;
    }
}
