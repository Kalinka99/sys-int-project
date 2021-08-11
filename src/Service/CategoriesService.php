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

use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class CategoryService.
 */
class CategoriesService
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var CategoriesRepository $categoriesRepository
     */
    private CategoriesRepository $categoriesRepository;

    /**
     * CategoriesService constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }

    /**
     * Adds a category.
     * @param $category
     * @return $this
     */
    public function addCategory($category): self
    {
        $this->entityManager->persist($category);
        return $this;
    }

    /**
     * Removes a category.
     * @param $category
     * @return $this
     */
    public function removeCategory($category): self
    {
        $this->entityManager->remove($category);

        return $this;
    }

    /**
     * Updates database.
     */
    public function executeUpdateOnDatabase(): void
    {
        $this->entityManager->flush();
    }
//
//    public function findAllCategories(){
//        return $this->categoriesRepository->findAll();
//    }

}
