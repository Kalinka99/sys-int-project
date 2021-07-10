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
 * Class UsersService.
 */
class UsersService
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * UsersService constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }

    /**
     * Edits the user.
     * @param $user
     * @return $this
     */
    public function editUser($user): self
    {
        $this->entityManager->persist($user);
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

