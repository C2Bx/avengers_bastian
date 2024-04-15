<?php

namespace App\DataFixtures;

use App\Entity\MarquePage;
use App\Entity\MotsCles;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class MarquePageFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $motsClesExistant = $manager->getRepository(MotsCles::class)->findAll();
        
        if (count($motsClesExistant) === 0) {
            throw new \Exception("Aucun mot clé n'a été trouvé. Assurez-vous que les MotsClesFixtures sont exécutées en premier.");
        }
        
        for ($j = 0; $j < 10; $j++) {
            $marquePage = new MarquePage();
            $marquePage->setUrl($faker->url);
            $marquePage->setDateCreation($faker->dateTimeThisYear);
            $marquePage->setCommentaire($faker->text);

            $randomMotsCles = $faker->randomElements($motsClesExistant, $faker->numberBetween(1, count($motsClesExistant)));

            foreach ($randomMotsCles as $motCleObj) {
                $marquePage->addMotsCle($motCleObj);
            }

            $manager->persist($marquePage);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            MotsClesFixtures::class,
        ];
    }
}
