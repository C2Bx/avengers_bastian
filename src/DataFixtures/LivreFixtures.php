<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Livre;
use App\Entity\Auteur;
use Faker\Factory;

class LivreFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

       
        $phrases = [
            'Le secret de {auteur}',
            'Les aventures de {auteur}',
            'L\'énigme de {auteur}',
            'Le destin de {auteur}',
            'La quête de {auteur}',
            'Le mystère de {auteur}',
            'La légende de {auteur}',
            '{auteur} et le trésor caché',
            '{auteur} : une histoire de passion',
            '{auteur} et la prophétie oubliée',
        ];

        
        $auteurs = $manager->getRepository(Auteur::class)->findAll();

       
        for ($i = 0; $i < 50; $i++) {
            $livre = new Livre();
            
           
            if (count($auteurs) == 0) {
                $auteur = "Inconnu";
            } else {
                $auteur = $faker->randomElement($auteurs);
            }

           
            $titre = $faker->randomElement($phrases);
            $titre = str_replace('{auteur}', $auteur->getNom(), $titre);

            
            $anneeParution = $faker->dateTimeBetween('-50 years', 'now');
            $nombrePages = $faker->numberBetween(50, 1000);
            $genre = $faker->randomElement(['Roman', 'Policier', 'Aventure', 'Science-Fiction', 'Fantastique', 'Historique']);
            $isbn = $faker->isbn13;

           
            $livre->setTitre($titre);
            $livre->setAuteur($auteur);
            $livre->setAnneeParution($anneeParution);
            $livre->setNombrePages($nombrePages);
            $livre->setGenre($genre);
            $livre->setIsbn($isbn);

            $manager->persist($livre);
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
