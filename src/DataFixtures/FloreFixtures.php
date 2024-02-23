<?php

namespace App\DataFixtures;

use App\Entity\Flore;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class FloreFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $floreData = [
            ['img' => 'img/flore/1.jpg', 'commentaire' => 'Une plante exotique avec des cônes.'],
            ['img' => 'img/flore/2.jpg', 'commentaire' => 'Une plante carnivore colorée.'],
            ['img' => 'img/flore/3.jpg', 'commentaire' => 'Une tulipe vibrante aux couleurs contrastées.'],
            ['img' => 'img/flore/4.jpg', 'commentaire' => 'Un pansy, également connu sous le nom de pensée sauvage.'],
            ['img' => 'img/flore/5.jpg', 'commentaire' => 'Des orchidées blanches élégantes.'],
        ];

        foreach ($floreData as $data) {
            $flore = new Flore();
            $flore->setImg($data['img']);
            $flore->setCommentaire($data['commentaire']);
            $manager->persist($flore);
        }

        $manager->flush();
    }
}
