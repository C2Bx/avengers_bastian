<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Auteur;

class AuteurFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $auteur = new Auteur();
            $auteur->setNom('NomAuteur' . $i);
            $auteur->setPrenom('PrenomAuteur' . $i);
            $manager->persist($auteur);

            // Référencement pour l'utilisation dans d'autres fixtures si nécessaire
            $this->addReference('auteur_' . $i, $auteur);
        }

        $manager->flush();
    }
}
