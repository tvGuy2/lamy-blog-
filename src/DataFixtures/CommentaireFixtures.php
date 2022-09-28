<?php

namespace App\DataFixtures;

use App\Entity\Commentaire;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Psr\Log\NullLogger;

class CommentaireFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create("fr_FR");
        for ($i = 0; $i <= 120; $i++) {
            $commentaire = new Commentaire();
            $commentaire->setContenu($faker->paragraphs(2, true));
            $commentaire->setCreateAt($faker->dateTimeBetween('-6 months'));

            //Associer l'article à une catégorie
            //Récupérer une référence d'une catégorie
            $numUtilisateur = $faker->numberBetween(1, 11);

            if ($faker->numberBetween(1,3) != 3){
                $commentaire->setUtilisateurId($this->getReference("utilisateur" . $numUtilisateur  ));
            }

            $numArticle = $faker->numberBetween(0,100);
            $commentaire->setArticleId($this->getReference("article" . $numArticle));



            // Générer l'ordre INSERT
            // INSERT INTO values ("Titre 1", "Contenu 1 ")
            $manager->persist($commentaire);

            $manager->flush();
        }
    }


    public function getDependencies()
    {
        return [
            ArticleFixtures::class,
            UtilisateurFixtures::class
        ];
    }
}
