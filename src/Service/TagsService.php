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
 * Class TagsService.
 */
class TagsService
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * TagsService constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }

    /**
     * Adds a tag.
     * @param $tag
     * @return $this
     */
    public function addTag($tag): self
    {
        $this->entityManager->persist($tag);
        return $this;
    }

    /**
     * Removes a tag.
     * @param $tag
     * @return $this
     */
    public function removeTag($tag): self
    {
        $this->entityManager->remove($tag);

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
