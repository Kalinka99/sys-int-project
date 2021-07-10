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

use Doctrine\ORM\EntityManagerInterface;

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
     * ArticlesService constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
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
