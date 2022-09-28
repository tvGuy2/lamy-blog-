<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Categorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class ArticleFixtures extends Fixture implements DependentFixtureInterface
{
    private SluggerInterface $slugger;

    //Demander à symfony d'injecter le slugger au niveau du constructeur

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        // Initialiser faker
        $faker = Factory::create("fr_FR");
        for ($i=0;$i<=100;$i++) {
            $article = new Article();
            $article->setTitre($faker->words($faker->numberBetween(3,10),true))
                    ->setContenu($faker->paragraphs(3,true))
                    ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                    ->setSlug($this->slugger->slug($article->getTitre())->lower());

            //Associer l'article à une catégorie
            //Récupérer une référence d'une catégorie
            $numCategorie = $faker->numberBetween(0,8);
            $article->setCategorie($this->getReference("categorie".$numCategorie));

            // Générer l'ordre INSERT
            // INSERT INTO values ("Titre 1", "Contenu 1 ")
            $manager->persist($article);
        }
            // Envoyer l'ordre INSERT vers la BDD
            $manager->flush();

    }

    public function getDependencies()
    {
        return [
          CategorieFixtures::class
        ];
    }
}
