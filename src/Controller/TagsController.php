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
 * Class TagsController
 * @package App\Controller
 */
class TagsController extends AbstractController
{
    /**
     * @var TagsRepository
     */
    private TagsRepository $tagsRepository;
    /**
     * @var ActionOnDbService
     */
    private ActionOnDbService $actionOnDb;

    /**
     * TagsController constructor.
     * @param TagsRepository $tagsRepository
     * @param ActionOnDbService $actionOnDb
     */
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
     * Shows a list of tags.
     * @Route("/", name="tags_index", methods={"GET"})
     * @return Response
     */
    public function index(): Response
    {
        $tags = $this->tagsRepository->findAll();

        return $this->render('tags/index.html.twig', [
            'tags' => $tags,
        ]);
    }

    /**
     * Creating a new tag.
     * @Route("/new", name="tags_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $tag = new Tags();
        $form = $this->createForm(TagsType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

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
     * Shows a tag with a list of articles with this tag.
     * @Route("/{id}", name="tags_show", methods={"GET"})
     * @param Tags $tag
     * @return Response
     */
    public function show(Tags $tag): Response
    {
        return $this->render('tags/show.html.twig', [
            'tag' => $tag,
            'articles' => $tag->getArticles()
        ]);
    }

    /**
     * Editing a tag.
     * @Route("/{id}/edit", name="tags_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Tags $tag
     * @return Response
     */
    public function edit(Request $request, Tags $tag): Response
    {
        $form = $this->createForm(TagsType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

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
     * Deleting a tag.
     * @Route("/{id}", name="tags_delete", methods={"POST"})
     * @param Request $request
     * @param Tags $tag
     * @return Response
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