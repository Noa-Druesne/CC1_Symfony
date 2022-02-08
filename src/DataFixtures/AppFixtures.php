<?php

namespace App\DataFixtures;

use App\Entity\Activite;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create("fr_FR");
        for ($i=1; $i<=10; $i++) {
            $activite = new Activite();
            $activite->setNom($faker->sentence);
            $activite->setDescription($faker->paragraph);
            $manager->persist($activite);
        }
        $manager->flush();
    }
}
