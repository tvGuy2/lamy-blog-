<?php

namespace App\DataFixtures;

use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UtilisateurFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker =Factory::create("fr_FR");
        for ($i=1; $i<=11; $i++){
            $utilisateur = new Utilisateur();
            $utilisateur->setPrenom($faker->firstName());
            $utilisateur->setNom($faker->lastName());
            $utilisateur->setPseudo($faker->word());


            //Créer une référence sur la catégorie
            $this->addReference("utilisateur".$i,$utilisateur);

            $manager->persist($utilisateur);

        }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
