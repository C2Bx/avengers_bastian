<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\MotsCles;
use Faker\Factory;

class MotsClesFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $motsCles = ['Technologie', 'Actualités', 'Sport', 'Cuisine', 'Voyage', 'Musique', 'Art', 'Cinéma', 'Science', 'Politique'];

        foreach ($motsCles as $motCle) {
            $motCleObj = new MotsCles();
            $motCleObj->setMotCle($motCle);
            $manager->persist($motCleObj);
        }

        $manager->flush();
    }
}

