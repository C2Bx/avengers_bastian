<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\MotsCles;

class MotsClesFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $motsClesUniques = ['Technologie', 'Actualités', 'Sport', 'Cuisine', 'Voyage', 'Musique', 'Art', 'Cinéma', 'Science', 'Politique'];

        foreach ($motsClesUniques as $motCleLabel) {
            $existingMotCle = $manager->getRepository(MotsCles::class)->findOneBy(['motCle' => $motCleLabel]);
            if (!$existingMotCle) {
                $motCle = new MotsCles();
                $motCle->setMotCle($motCleLabel);
                $manager->persist($motCle);
            }
        }

        $manager->flush();
    }
}
