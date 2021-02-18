<?php

namespace App\DataFixtures;

use App\Entity\Lieu;
use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class LieuFixtures extends Fixture
{
    public const LIEU_REFERENCE = 'lieu';

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        $lieu = null;
        for ($i = 0; $i < 30; $i++) {

            /* @var $ville Ville */
            $ville = $this->getReference('ville_' . $faker->numberBetween(0 , 9));

            $lieu = new Lieu();
            $lieu->setName($faker->city);
            $lieu->setRue($faker->streetAddress);
            $lieu->setVille($ville);
            $lieu->setLatitude($faker->latitude);
            $lieu->setLongitude($faker->longitude);

            $manager->persist($lieu);
            $this->addReference('lieu_' . $i, $lieu);

        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return array(
            AVilleFixtures::class,
        );
    }
}
