<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class UserFixtures extends Fixture
{
    public const USER_REFERENCE = 'user';

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        /* @var $campus Campus */
        $campus = $this->getReference(CampusFixtures::CAMPUS_REFERENCE);

        for ($i = 0; $i < 200; $i++) {
            $user = new User();
            $user->setName($faker->lastName);
            $user->setSurname($faker->firstName);
            $user->setUsername($faker->userName);
            $user->setEmail($faker->email);
            $user->setPassword($faker->sha256);
            $user->setRoles(['ROLE_USER']);
            $user->setCampus($campus);
            $user->setActif($faker->numberBetween($min = 0, $max = 1));
            $user->setTelephone($faker->phoneNumber);
            $user->setUrlPhoto($faker->imageUrl($width = 640, $height = 480));

            $manager->persist($user);
        }

        $this->addReference(self::USER_REFERENCE, $user);

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            CampusFixtures::class,

        );
    }
}
