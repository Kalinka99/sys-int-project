<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Kalina Muchowicz <https://github.com/Kalinka99>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Users as User;
use App\Entity\Articles as Article;
use App\Entity\Comments as Comment;
use App\Entity\Categories as Category;
use App\Entity\Tags as Tag;

/**
 * Class AppFixtures
 * @package App\DataFixtures
 */
class AppFixtures extends Fixture
{
    /**
     * Fills the database with fixtures data.
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $user = (new User())
            ->setEmail("admin@admin.com")
            ->setPassword(password_hash("admin1234", PASSWORD_BCRYPT))
            ->setRoles(["ROLE_ADMIN"])
            ->setAbout("Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.")
            ->setContact("Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.");
        $manager->persist($user);

        $category = (new Category())
            ->setName("Kategoria 1");
        $manager->persist($category);

        for($i=0; $i<10; ++$i) {
            $article = (new Article())
                ->setTitle("Tytuł artykułu")
                ->setMainText("Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum")
                ->setCreated(new \DateTime("now"))
                ->setCategories($category)
                ->setUsers($user);
            $manager->persist($article);
        }

        $category = (new Category())
            ->setName("Kategoria 2");
        $manager->persist($category);

        for ($i = 0; $i < 10; ++$i) {
            $article = (new Article())
                ->setTitle("Tytuł artykułu")
                ->setMainText("Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum")
                ->setCreated(new \DateTime("now"))
                ->setCategories($category)
                ->setUsers($user);
            $manager->persist($article);
        }

        $category = (new Category())
            ->setName("Kategoria 3");
        $manager->persist($category);

        for ($i = 0; $i < 10; ++$i) {
            $article = (new Article())
                ->setTitle("Tytuł artykułu")
                ->setMainText("Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum")
                ->setCreated(new \DateTime("now"))
                ->setCategories($category)
                ->setUsers($user);
            $manager->persist($article);
        }

        $tag = (new Tag())
            ->setName("#tag1");
        $manager->persist($tag);

        $tag = (new Tag())
            ->setName("#tag2");
        $manager->persist($tag);

        $tag = (new Tag())
            ->setName("#tag3");
        $manager->persist($tag);

        for ($i = 0; $i < 3; ++$i) {
            $comment = (new Comment())
                ->setArticles($article)
                ->setAuthorUsername("Anon")
                ->setAuthorEmail("anon@example.com")
                ->setMainText("Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.")
                ->setCreated(new\DateTime("now"));
            $manager->persist($comment);
        }

        $manager->flush();
            }
        }
