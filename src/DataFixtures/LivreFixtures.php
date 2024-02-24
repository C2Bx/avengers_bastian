<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Livre;
use App\Entity\Auteur;
use Faker\Factory;

class LivreFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $auteurs = $manager->getRepository(Auteur::class)->findAll();

        foreach ($auteurs as $auteur) {
            $nombreLivres = rand(1, 10); 
            for ($i = 0; $i < $nombreLivres; $i++) {
                $livre = new Livre();
                $livre->setTitre($faker->sentence());
                $livre->setAuteur($auteur);
                $livre->setAnneeParution($faker->dateTimeThisCentury);
                $livre->setNombrePages($faker->numberBetween(50, 1000));
                $livre->setGenre($faker->word);
                $livre->setIsbn($faker->isbn13);
                $manager->persist($livre);
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            AuteurFixtures::class,
        ];
    }
}
