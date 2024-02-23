<?php

// MarquePageFixtures.php

namespace App\DataFixtures;

use App\Entity\MarquePage;
use App\Entity\MotsCles;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MarquePageFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Création de 25 mots-clés différents
        for ($i = 1; $i <= 25; $i++) {
            $motCle = new MotsCles();
            $motCle->setMotCle("Mot-clé $i");
            $manager->persist($motCle);
            // Ajouter une référence pour chaque mot-clé
            $this->addReference('mot_cle_'.$i, $motCle);
        }

        // Création de marque-pages avec 2 à 5 mots-clés aléatoires associés
        for ($j = 1; $j <= 10; $j++) {
            $marquePage = new MarquePage();
            $marquePage->setUrl("https://exemple.com/page$j");
            $marquePage->setDateCreation(new \DateTime());
            $marquePage->setCommentaire("Commentaire pour la page $j"); // Correction ici

            // Sélection aléatoire de 2 à 5 mots-clés
            $randomMotsCles = [];
            $nbMotsCles = rand(2, 5);
            for ($k = 1; $k <= $nbMotsCles; $k++) {
                // Choix aléatoire d'un mot-clé parmi les références ajoutées
                $randomMotsCles[] = $this->getReference('mot_cle_' . rand(1, 25));
            }

            foreach ($randomMotsCles as $motCle) {
                $marquePage->addMotsCle($motCle);
            }

            $manager->persist($marquePage);
        }

        $manager->flush(); // Enregistrer les changements dans la base de données
    }
}
