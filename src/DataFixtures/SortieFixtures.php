<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class SortieFixtures extends Fixture
{
    public const SORTIE_REFERENCE = 'sortie';

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        $sortie = null;
        for ($i = 0; $i < 50; $i++) {
            /* @var $user User */
            $user = $this->getReference('user_' . $faker->numberBetween(0,199));

            /* @var $campus Campus */
            $campus = $this->getReference('camp_' . $faker->numberBetween(1,5));

            /* @var $lieu Lieu */
            $lieu = $this->getReference('lieu_' . $faker->numberBetween(0,29));

            /* @var $etat Etat */
            $etat = $this->getReference( 'cat_' . $faker->numberBetween(1, 7));

            $sortie = new Sortie();
            $sortie->setName($faker->word);
            $sortie->setDescription($faker->realText(1000));
            $sortie->setCampus($campus);
            $sortie->setDeadline($faker->dateTimeThisMonth());
            $sortie->setStartedDateTime($faker->dateTimeBetween($startDate = '30 days', $endDate = '90 days', $timezone = null));
            $sortie->setDuration($faker->numberBetween($min = 1, $max = 4));
            $sortie->setMaxNbOfRegistration($faker->numberBetween($min = 7, $max = 9));
            $sortie->setEtat($etat);
            $sortie->setLieu($lieu);

            for ($j = 0; $j < $faker->numberBetween($min = 3, $max = 7); $j++) $sortie->addUser($user);

            $sortie->setOrganiser($user);

            $manager->persist($sortie);

        }
        $this->addReference(self::SORTIE_REFERENCE, $sortie);
        $manager->flush();
    }

    public function getDependencies() : array
    {
        return array(
            AVilleFixtures::class,
            LieuFixtures::class,
            EtatFixtures::class,
            CampusFixtures::class,

        );
    }
}
