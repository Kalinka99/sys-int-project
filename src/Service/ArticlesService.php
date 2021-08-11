<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Kalina Muchowicz <https://github.com/Kalinka99>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Service;

use App\Repository\ArticlesRepository;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class ArticlesService.
 */
class ArticlesService
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var ArticlesRepository
     */
    private ArticlesRepository $articlesRepository;

    /**
     * Paginator.
     *
     * @var PaginatorInterface
     */
    private PaginatorInterface $paginator;

    /**
     * ArticlesService constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager, PaginatorInterface $paginator, ArticlesRepository $articlesRepository) {
        $this->entityManager = $entityManager;
        $this->paginator = $paginator;
        $this->articlesRepository = $articlesRepository;
    }

    /**
     * Create paginated list.
     * @param int $page Page number
     * @return PaginationInterface Paginated list
     */
    public function createPaginatedList(int $page): PaginationInterface
    {
            $articles = $this->articlesRepository->findBy([], ['id'=>'DESC']);
            return $articles = $this->paginator->paginate($articles, $page,10);
    }


    /**
     * Adds an article.
     * @param $article
     * @return $this
     */
    public function addArticle($article): self
    {
        $this->entityManager->persist($article);
        return $this;
    }

    /**
     * Removes an article.
     * @param $article
     * @return $this
     */
    public function removeArticle($article): self
    {
        $this->entityManager->remove($article);

        return $this;
    }

    /**
     * Updates database.
     */
    public function executeUpdateOnDatabase(): void
    {
        $this->entityManager->flush();
    }
}
