<?php

namespace App\DataFixtures;

use App\Entity\Faune;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class FauneFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $fauneData = [
            ['img' => 'img/faune/1.jpg', 'commentaire' => 'Un perroquet coloré aux couleurs vives.'],
            ['img' => 'img/faune/2.jpg', 'commentaire' => 'Un panda roux perché dans un arbre.'],
            ['img' => 'img/faune/3.jpg', 'commentaire' => 'Un tigre majestueux dans son habitat naturel.'],
            ['img' => 'img/faune/4.jpg', 'commentaire' => 'Un cheval brun dans un champ ouvert.'],
            ['img' => 'img/faune/5.jpg', 'commentaire' => 'Un paon affichant ses plumes spectaculaires.'],
        ];

        foreach ($fauneData as $data) {
            $faune = new Faune();
            $faune->setImg($data['img']);
            $faune->setCommentaire($data['commentaire']);
            $manager->persist($faune);
        }

        $manager->flush();
    }
}
