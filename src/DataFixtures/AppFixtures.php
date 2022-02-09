<?php

namespace App\DataFixtures;

use App\Entity\Activite;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create("fr_FR");
        $user = new User();
        $user->setNom("John");
        $user->setPrenom("Patrick");
        $user->setPassword("ouioui");

        $activite = new Activite();
        $activite->setNom("Piscine");
        $activite->setDescription("C'est de la piscine quoi.");
        $activite->setAnimateur($user);
        $manager->persist($user);
        $manager->persist($activite);
        $manager->flush();
    }
}
