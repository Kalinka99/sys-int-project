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
 * Class CommentsService.
 */
class CommentsService
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * CommentsService constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }

    /**
     * Adds a comment.
     * @param $comment
     * @return $this
     */
    public function addComment($comment): self
    {
        $this->entityManager->persist($comment);
        return $this;
    }

    /**
     * Removes a comment.
     * @param $comment
     * @return $this
     */
    public function removeComment($comment): self
    {
        $this->entityManager->remove($comment);

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
