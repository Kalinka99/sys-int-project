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

use App\Entity\Articles;
use App\Entity\Comments;
use App\Form\ArticlesType;
use App\Form\CommentsType;
use App\Repository\ArticlesRepository;
use App\Repository\CategoriesRepository;
use App\Repository\TagsRepository;
use App\Service\ArticlesService;
use App\Service\CategoriesService;
use App\Service\CommentsService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/articles")
 * Class ArticlesController
 * @package App\Controller
 */
class ArticlesController extends AbstractController
{
    /**
     * @var ArticlesRepository
     */
    private ArticlesRepository $articleRepository;

    /**
     * @var CategoriesRepository
     */
    private CategoriesRepository $categoriesRepository;

    /**
     * @var TagsRepository
     */
    private TagsRepository $tagsRepository;

    /**
     * @var PaginatorInterface
     */
    private PaginatorInterface $paginator;

    /**
     * Categories service.
     * @var CategoriesService
     */
    private CategoriesService $categoriesService;

    /**
     * Comments Service.
     * @var CommentsService
     */
    private CommentsService $commentsService;

    /**
     * Articles service.
     * @var ArticlesService
     */
    private ArticlesService $articlesService;

    /**
     * ArticlesController constructor.
     * @param ArticlesRepository $articleRepository
     * @param CategoriesRepository $categoriesRepository
     * @param TagsRepository $tagsRepository
     * @param PaginatorInterface $paginator
     * @param CategoriesService $categoriesService
     * @param ArticlesService $articlesService
     * @param CommentsService $commentsService
     */
    public function __construct(ArticlesRepository $articleRepository, CategoriesRepository $categoriesRepository, TagsRepository $tagsRepository, PaginatorInterface $paginator, CategoriesService $categoriesService, ArticlesService $articlesService, CommentsService $commentsService)
    {
        $this->articleRepository = $articleRepository;
        $this->categoriesRepository = $categoriesRepository;
        $this->tagsRepository = $tagsRepository;
        $this->paginator = $paginator;
        $this->categoriesService = $categoriesService;
        $this->commentsService = $commentsService;
        $this->articlesService = $articlesService;
    }

    /**
     * Shows all articles with pagination of ten records per page.
     * @Route("/{page<\d+>?1}", name="articles_index", methods={"GET"})
     * @param int $page
     * @return Response
     */
    public function index(int $page): Response
    {
        $articles = $this->articleRepository->findBy([], ['id'=>'DESC']);
        $articles = $this->paginator->paginate($articles, $page,10);
        $categories = $this->categoriesRepository->findAll();

        return $this->render('articles/index.html.twig', [
            'articles' => $articles,
            'categories' => $categories,
        ]);
    }

    /**
     * Creating a new article.
     * @Route("/new", name="articles_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $categories = $this->categoriesRepository->findAll();
        $tags = $this->tagsRepository->findAll();

        $options = [
            'categories' => $this->serializeObject($categories),
            'tags' => $this->serializeObject($tags),
        ];

        $form = $this->createForm(ArticlesType::class, null, $options);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $article = (new Articles())
                ->setTitle($data['title'])
                ->setMainText($data['mainText'])
                ->setCreated(new \DateTime('now'))
                ->setCategories($this->categoriesRepository->findOneById($data['categories']))
                ->setUsers($this->getUser())
                ->addTag($this->tagsRepository->findOneById($data['tags']));

            $this->articlesService
                ->addArticle($article)
                ->executeUpdateOnDatabase();

            return $this->redirectToRoute('articles_index');
        }

        return $this->render('articles/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Shows an article with comments and comment form.
     * @Route("/show/{id}", name="articles_show", methods={"GET", "POST"})
     * @param Articles $article
     * @param Request $request
     * @return Response
     */
    public function show(Articles $article, Request $request): Response
    {
        $form = $this->createForm(CommentsType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $comment = (new Comments())
                ->setAuthorUsername($data['authorUsername'])
                ->setAuthorEmail($data['authorEmail'])
                ->setMainText($data['mainText'])
                ->setCreated(new \DateTime('now'))
                ->setArticles($article);

            $this->commentsService
                ->addComment($comment)
                ->executeUpdateOnDatabase();
        }

        return $this->render('articles/show.html.twig', [
            'article' => $article,
            'form' => $form->createView()
        ]);
    }

    /**
     * Editing an article.
     * @Route("/edit/{id}", name="articles_edit_1", methods={"GET","POST"})
     * @param Request $request
     * @param Articles $article
     * @return Response
     */
    public function edit(Request $request, Articles $article): Response
    {
        $categories = $this->categoriesRepository->findAll();

        $options = [
            'categories' => $this->serializeObject($categories),
            'removeTags' => true,
            'tagsToRemove' => $this->serializeObject($article->getTags())
        ];

        $form = $this->createForm(ArticlesType::class, null, $options);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $article
                ->setTitle($data['title'])
                ->setMainText($data['mainText'])
                ->setCategories($this->categoriesRepository->findOneById($data['categories']));

            $this->articlesService
                ->addArticle($article)
                ->executeUpdateOnDatabase();

            return $this->redirectToRoute('articles_index');
        }
        return $this->render('articles/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Shows the list of articles by category.
     * @Route("/by-category/{page<\d+>?1}/{categoryId}", name="articles_by_category", methods={"GET"})
     * @param int $page
     * @param int $categoryId
     * @return Response
     */
    public function articlesCategory(int $page, int $categoryId): Response
    {
        $category = $this->categoriesRepository->findOneById($categoryId);
        $articles = $category->getArticles();
        $articles = $this->paginator->paginate($articles, $page,10);
        $categories = $this->categoriesRepository->findAll();

        return $this->render('articles/index.html.twig', [
            'articles' => $articles,
            'categories' => $categories,
        ]);
    }

    /**
     * Shows the list of articles by tag.
     * @Route("/by-tag/{page<\d+>?1}/{tagId}", name="articles_by_tags", methods={"GET"})
     * @param int $page
     * @param int $tagId
     * @return Response
     */
    public function articlesTag(int $page, int $tagId): Response
    {
        $tag = $this->tagsRepository->findOneById($tagId);
        $articles = $tag->getArticles();
        $articles = $this->paginator->paginate($articles, $page,10);

        $tags = $this->tagsRepository->findAll();
        $categories = $this->categoriesRepository->findAll();

        return $this->render('articles/index.html.twig', [
            'articles' => $articles,
            'tags' => $tags,
            'categories' => $categories
        ]);
    }

    /**
     * Deletes an article.
     * @Route("/{id}", name="articles_delete", methods={"POST"})
     * @param Request $request
     * @param Articles $article
     * @return Response
     */
    public function delete(Request $request, Articles $article): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $article->setUsers(null);
            $article->setCategories(null);
            if(!empty($article->getComments() )){
                foreach ($article->getComments() as $comment){
                    $article->removeComment($comment);
                    $this->commentsService->removeComment($comment);
                }
            }
            if (!empty($article->getTags() )){
                foreach ($article->getTags() as $tag){
                    $article->removeTag($tag);
                }
            }

            $this->articlesService
                ->removeArticle($article)
                ->executeUpdateOnDatabase();
        }
        return $this->redirectToRoute('articles_index');
    }

    /**
     * Serializes objects.
     * @param $objects
     * @return array
     */
    private function serializeObject($objects): array
    {
        $response = [];
        foreach ($objects as $object){
            $response[$object->getName()] = $object->getId();
        }
        return $response;
    }
}

