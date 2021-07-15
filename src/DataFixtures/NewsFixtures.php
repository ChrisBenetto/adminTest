<?php

namespace App\DataFixtures;

use App\Entity\News;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;

class NewsFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i < 10; $i++) {
            $news = new News();
            /* Article publish at least 1 month ago and ending after one month */
            $randomIntForPublishDate = rand(1626307200, 1623715200);
            $randomIntForPublishEnding = rand(1628985600, 1626307200);
            $news->setTitle("Titre de l\'actualité n°$i")
                ->setSlug("Résume de l\'actualité n°$i")
                ->setCreateAt(new \DateTime())
                ->setContent("<p>Contenu de l'actualité n°$i </p>")
                ->setPublicationDate(new \DateTime(date("Y-m-d", $randomIntForPublishDate)))
                ->setPublicationEnding(new \DateTime(date("Y-m-d", $randomIntForPublishEnding)));

            $manager->persist($news);
        }

        $manager->flush();
    }
}
