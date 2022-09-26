<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Initialiser faker
        $faker = Factory::create("fr_FR");
        for ($i=0;$i<=50;$i++) {
            $article = new Article();
            $article->setTitre($faker->words($faker->numberBetween(3,10),true))
                ->setContenu($faker->paragraphs(3,true))
                ->setCreatedAt($faker->dateTimeBetween('-6 months'));
            // Générer l'ordre INSERT
            // INSERT INTO values ("Titre 1", "Contenu 1 ")
            $manager->persist($article);
        }
            // Envoyer l'ordre INSERT vers la BDD
            $manager->flush();

    }
}
