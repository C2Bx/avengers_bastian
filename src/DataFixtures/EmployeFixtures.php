<?php

namespace App\DataFixtures;

use App\Entity\Employe;
use App\Entity\Adresse;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class EmployeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR'); 
        for ($i = 0; $i < 10; $i++) {
            $employe = new Employe();
            $employe->setNom($faker->lastName);
            $employe->setPrenom($faker->firstName);

            $adresse = new Adresse();
            $adresse->setRue($faker->streetName);
            $adresse->setVille($faker->city);
            $adresse->setCodePostal($faker->postcode);
            
            $employe->setAdresse($adresse); // Correct this line

            $manager->persist($adresse);
            $manager->persist($employe);
        }

        $manager->flush();
    }
}
