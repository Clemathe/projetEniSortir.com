<?php

namespace App\DataFixtures;

use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Faker\Provider\Address;

class AVilleFixtures extends Fixture
{
    public const VILLE_REFERENCE = 'ville';

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {

            $ville = new Ville();
            $ville->setName($faker->city);
            $ville->setCodePostal(Address::postcode());

            $manager->persist($ville);

            $this->addReference('ville_' . $i, $ville);

        }
        $manager->flush();
    }

}
