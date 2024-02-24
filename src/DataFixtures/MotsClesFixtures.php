<?php

// MotsClesFixtures.php

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

        for ($i = 0; $i < 25; $i++) {
            $motsCles = new MotsCles();
            $motsCles->setMotCle($faker->word);
            $manager->persist($motsCles);
        }

        $manager->flush();
    }
}
