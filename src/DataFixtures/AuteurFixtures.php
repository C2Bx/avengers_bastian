<?php

// AuteurFixtures.php

namespace App\DataFixtures;

use App\Entity\Auteur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AuteurFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $auteurs = [
            ['nom' => 'Nom1', 'prenom' => 'Prenom1'],
            ['nom' => 'Nom2', 'prenom' => 'Prenom2'],
            ['nom' => 'Nom3', 'prenom' => 'Prenom3'],
            // Ajoutez autant d'auteurs que nécessaire
        ];

        foreach ($auteurs as $key => $data) {
            $auteur = new Auteur();
            $auteur->setNom($data['nom']);
            $auteur->setPrenom($data['prenom']);
            $manager->persist($auteur);

            $this->addReference('auteur_' . $key, $auteur);
        }

        $manager->flush();
    }
}
