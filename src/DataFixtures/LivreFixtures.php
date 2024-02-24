<?php

namespace App\DataFixtures;

use App\Entity\Livre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LivreFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $lettres = range('A', 'Z');

        // Créer des livres avec des auteurs différents
        for ($i = 0; $i < 10; $i++) {
            // Récupérer une référence d'auteur différente pour chaque livre
            $auteur = $this->getReference('auteur_' . $i);

            foreach ($lettres as $lettre) {
                $livre = new Livre();
                $livre->setTitre($lettre . ' Livre ' . $i); // Ajouter la lettre au titre du livre
                $livre->setAuteur($auteur);
                $livre->setAnneeParution(new \DateTime('now'));
                $livre->setNombrePages(100 + $i);
                $livre->setGenre('Genre ' . $i);
                $livre->setIsbn('978-3-16-148410-' . $i);
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
