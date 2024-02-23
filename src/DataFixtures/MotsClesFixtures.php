<?php

// MotsClesFixtures.php

namespace App\DataFixtures;

use App\Entity\MotsCles;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MotsClesFixtures extends Fixture
{
    public const MOTS_CLES_REFERENCE = 'mots-cles';

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 25; $i++) {
            $motsCles = new MotsCles();
            $motsCles->setMotCle('Mot-clé '.$i);
            $manager->persist($motsCles);

            // Ajout de références pour les utiliser dans d'autres fixtures
            $this->addReference(self::MOTS_CLES_REFERENCE.'_'.$i, $motsCles);
        }

        $manager->flush();
    }
}
