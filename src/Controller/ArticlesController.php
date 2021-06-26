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
use App\Repository\TagsRepository;
use App\Service\ActionOnDbService;
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

    private ArticlesRepository $articleRepository;
    private CategoriesRepository $categoriesRepository;
    private TagsRepository $tagsRepository;
    private PaginatorInterface $paginator;
    private ActionOnDbService $actionOnDb;

    public function __construct
    (
        ArticlesRepository $articleRepository,
        CategoriesRepository $categoriesRepository,
        TagsRepository $tagsRepository,
        PaginatorInterface $paginator,
        ActionOnDbService $actionOnDb
    )

    {
        $this->articleRepository = $articleRepository;
        $this->categoriesRepository = $categoriesRepository;
        $this->tagsRepository = $tagsRepository;
        $this->paginator = $paginator;
        $this->actionOnDb = $actionOnDb;
    }

/**
 * @Route("/{page<\d+>?1}", name="articles_index", methods={"GET"})
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
 * @Route("/new", name="articles_new", methods={"GET","POST"})
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

        if ($request->isMethod('POST') && $form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $article = (new Articles())
                ->setTitle($data['title'])
                ->setMainText($data['mainText'])
                ->setCreated(new \DateTime('now'))
                ->setCategories($this->categoriesRepository->findOneById($data['categories']))
                ->setUsers($this->getUser())
                ->addTag($this->tagsRepository->findOneById($data['tags']));

            $this->actionOnDb
                ->addElement($article)
                ->executeUpdateOnDatabase();

            return $this->redirectToRoute('articles_index');
        }

        return $this->render('articles/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/show/{id}", name="articles_show", methods={"GET", "POST"})
     */
    public function show(Articles $article, Request $request): Response
{
    $form = $this->createForm(CommentsType::class);
    $form->handleRequest($request);

    if ($request->isMethod('POST') && $form->isSubmitted() && $form->isValid()) {
        $data = $form->getData();

        $comment = (new Comments())
            ->setAuthorUsername($data['authorUsername'])
            ->setAuthorEmail($data['authorEmail'])
            ->setMainText($data['mainText'])
            ->setCreated(new \DateTime('now'))
            ->setArticles($article);

        $this->actionOnDb
            ->addElement($comment)
            ->executeUpdateOnDatabase();
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
    $categories = $this->categoriesRepository->findAll();

    $options = [
        'categories' => $this->serializeObject($categories),
        'removeTags' => true,
        'tagsToRemove' => $this->serializeObject($article->getTags())
    ];

    $form = $this->createForm(ArticlesType::class, null, $options);
    $form->handleRequest($request);

    if ($request->isMethod('POST') && $form->isSubmitted() && $form->isValid()) {
        $data = $form->getData();

        $article
            ->setTitle($data['title'])
            ->setMainText($data['mainText'])
            ->setCategories($this->categoriesRepository->findOneById($data['categories']));

        $this->actionOnDb
            ->addElement($article)
            ->executeUpdateOnDatabase();

        return $this->redirectToRoute('articles_index');
    }
    return $this->render('articles/edit.html.twig', [
        'article' => $article,
        'form' => $form->createView(),
    ]);
}

    /**
     * @Route("/by-category/{page<\d+>?1}/{categoryId}", name="articles_by_category", methods={"GET"})
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
     * @Route("/by-tag/{page<\d+>?1}/{tagId}", name="articles_by_tags", methods={"GET"})
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
     * @Route("/{id}", name="articles_delete", methods={"POST"})
     */
    public function delete(Request $request, Articles $article): Response
{
    if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
        $article->setUsers(null);
        $article->setCategories(null);
        if(!empty($article->getComments() )){
            foreach ($article->getComments() as $comment){
                $article->removeComment($comment);
                $this->actionOnDb->removeElement($comment);
            }
        }
        if (!empty($article->getTags() )){
            foreach ($article->getTags() as $tag){
                $article->removeTag($tag);
            }
        }

        $this->actionOnDb
            ->removeElement($article)
            ->executeUpdateOnDatabase();
    }

    return $this->redirectToRoute('articles_index');
}

    private function serializeObject($objects): array
{
    $response = [];

    foreach ($objects as $object){
        $response[$object->getName()] = $object->getId();
    }

    return $response;
}
}
?>