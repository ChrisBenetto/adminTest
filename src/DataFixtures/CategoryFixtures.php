<?php

namespace App\DataFixtures;

use App\Entity\News;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i < 6; $i++) {
            $category = new Category();
            $category->setTitle("Categorie n°$i")
                ->setSlug("Slug n°$i")
                ->setCreateAt(new \DateTime('now'));

            $manager->persist($category);
        }

        $manager->flush();
    }
}
