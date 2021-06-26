<?php

namespace App\Controller;

use App\Entity\Tags;
use App\Form\TagsType;
use App\Repository\TagsRepository;
use App\Service\ActionOnDbService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tags")
 */
class TagsController extends AbstractController
{
    private TagsRepository $tagsRepository;
    private ActionOnDbService $actionOnDb;

    public function __construct
    (
        TagsRepository $tagsRepository,
        ActionOnDbService $actionOnDb
    )
    {
        $this->tagsRepository = $tagsRepository;
        $this->actionOnDb = $actionOnDb;
    }

    /**
     * @Route("/", name="tags_index", methods={"GET"})
     */
    public function index(): Response
    {
        $tags = $this->tagsRepository->findAll();

        return $this->render('tags/index.html.twig', [
            'tags' => $tags,
        ]);
    }

    /**
     * @Route("/new", name="tags_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $tag = new Tags();
        $form = $this->createForm(TagsType::class, $tag);
        $form->handleRequest($request);

        if ($request->isMethod('POST') && $form->isSubmitted() && $form->isValid()) {

            $this->actionOnDb
                ->addElement($tag)
                ->executeUpdateOnDatabase();

            return $this->redirectToRoute('tags_index');
        }

        return $this->render('tags/new.html.twig', [
            'tag' => $tag,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tags_show", methods={"GET"})
     */
    public function show(Tags $tag): Response
    {
        return $this->render('tags/show.html.twig', [
            'tag' => $tag,
            'articles' => $tag->getArticles()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="tags_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Tags $tag): Response
    {
        $form = $this->createForm(TagsType::class, $tag);
        $form->handleRequest($request);

        if ($request->isMethod('POST') && $form->isSubmitted() && $form->isValid()) {

            $this->actionOnDb
                ->addElement($tag)
                ->executeUpdateOnDatabase();

            return $this->redirectToRoute('tags_index');
        }

        return $this->render('tags/edit.html.twig', [
            'tag' => $tag,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tags_delete", methods={"POST"})
     */
    public function delete(Request $request, Tags $tag): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tag->getId(), $request->request->get('_token'))) {
            $this->actionOnDb
                ->removeElement($tag)
                ->executeUpdateOnDatabase();
        }

        return $this->redirectToRoute('tags_index');
    }
}
?>