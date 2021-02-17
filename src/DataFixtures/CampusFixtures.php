<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CampusFixtures extends Fixture
{
    public const CAMPUS_REFERENCE = 'campus';


    public function load(ObjectManager $manager)
    {
        $camps = [
            1 => [
                'name' => 'Niort',
            ],
            2 => [
                'name' => 'Nantes',
            ],
            3 => [
                'name' => 'Rennes',
            ],
            4 => [
                'name' => 'Quimper',
            ],

        ];

        foreach ($camps as $key => $value) {
            $campus = new Campus();
            $campus->setName($value['name']);
            $manager->persist($campus);
        }
        $this->addReference(self::CAMPUS_REFERENCE, $campus);

        $manager->flush();

    }
}
