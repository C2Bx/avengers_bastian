<?php

namespace App\DataFixtures;

use App\Entity\MarquePage;
use App\Entity\MotsCles;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class MarquePageFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

      
        for ($j = 1; $j <= 10; $j++) {
            $marquePage = new MarquePage();
            $marquePage->setUrl($faker->url);
            $marquePage->setDateCreation($faker->dateTimeThisMonth);
            $marquePage->setCommentaire($faker->sentence); 
           
            $motsCles = ['Technologie', 'Actualités', 'Sport', 'Cuisine', 'Voyage', 'Musique', 'Art', 'Cinéma', 'Science', 'Politique'];
            $randomMotsCles = $faker->randomElements($motsCles, $faker->numberBetween(2, 5));

            foreach ($randomMotsCles as $motCle) {
                $motCleObj = new MotsCles();
                $motCleObj->setMotCle($motCle);
                $manager->persist($motCleObj);
                $marquePage->addMotsCle($motCleObj);
            }

            $manager->persist($marquePage);
        }

        $manager->flush(); 
    }
}
