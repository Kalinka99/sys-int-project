<?php

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