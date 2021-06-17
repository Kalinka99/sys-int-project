<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Users as User;
use App\Entity\Articles as Article;
use App\Entity\Comments as Comment;
use App\Entity\Categories as Category;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $category = (new Category())
            ->setName("Kategoria 1");
        $manager->persist($category);

        $category = (new Category())
            ->setName("Kategoria 2");
        $manager->persist($category);

        $category = (new Category())
            ->setName("Kategoria 3");
        $manager->persist($category);

        $user = (new User())
            ->setEmail("admin@admin.com")
            ->setPassword(password_hash("admin1234", PASSWORD_BCRYPT))
            ->setRoles(["ROLE_ADMIN"]);
        $manager->persist($user);

        for($i=0; $i<50; ++$i) {
            $article = (new Article())
                ->setTitle("Tytuł artykułu")
                ->setMainText("Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum")
                ->setCreated(new \DateTime("now"))
                ->setCategories($category)
                ->setUsers($user);
            $manager->persist($article);
        }

        for($i=0; $i<3; ++$i){
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
