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

class ActionOnDbService
{
    private EntityManagerInterface $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager
    )
    {
        $this->entityManager = $entityManager;
    }

    public function addElement($object): self
    {
        $this->entityManager->persist($object);

        return $this;
    }

    public function removeElement($object): self
    {
        $this->entityManager->remove($object);

        return $this;
    }

    public function executeUpdateOnDatabase(): void
    {
        $this->entityManager->flush();
    }
}