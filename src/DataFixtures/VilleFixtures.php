<?php

namespace App\DataFixtures;

use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class VilleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $faker = Faker\Factory::create('fr_FR');
            $ville = new Ville();
            $ville->setName($faker->city);
            $ville->setCodePostal(10000);
            $manager->persist($ville);

//        $this->addReference($ville->getId());
            $manager->flush();
        }
    }

}
