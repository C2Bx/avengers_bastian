<?php

namespace App\DataFixtures;

use App\Entity\Employe;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class EmployeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR'); // Crée une instance de Faker pour générer des données en français

        for ($i = 0; $i < 10; $i++) {
            $employe = new Employe();
            $employe->setNom($faker->lastName);
            $employe->setPrenom($faker->firstName);
            // Ajoutez ici d'autres propriétés si nécessaire
            // $employe->setEmail($faker->email);
            // $employe->setDepartement($faker->company);

            $manager->persist($employe);
        }

        $manager->flush();
    }
}
