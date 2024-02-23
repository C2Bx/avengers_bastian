<?php

// src/DataFixtures/LivreFixtures.php
namespace App\DataFixtures;

use App\Entity\Livre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LivreFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 15; $i++) {
            $livre = new Livre();
            $livre->setTitre('Livre '.$i);
            // Ici, vous devrez ajuster pour définir l'auteur en utilisant une entité Auteur existante.
            $livre->setAnneeParution(new \DateTime());
            $livre->setNombrePages(rand(100, 500));
            $livre->setGenre('Genre '.$i);
            $manager->persist($livre);
        }

        $manager->flush();
    }
}
