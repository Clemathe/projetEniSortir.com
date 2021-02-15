<?php

namespace App\DataFixtures;

use App\Entity\Etat;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EtatFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $etats = [
            1 => [
                'id'=> 1,
                'libelle' => 'Crées',
            ],
            2 => [
                'id'=> 2,
                'libelle' => 'Ouverte',
            ],
            3 => [
                'id'=> 3,
                'libelle' => 'Cloturée',
            ],
            4 => [
                'id'=> 4,
                'libelle' => 'En cours',
            ],
            5 => [
                'id'=> 5,
                'libelle' => 'Terminée',
            ],
            6 => [
                'id'=> 6,
                'libelle' => 'Annulée',
            ],
            7 => [
                'id'=> 7,
                'libelle' => 'Archivée',
            ]
        ];

        foreach($etats as $key => $value){
            $etat = new Etat();
            $etat->setLibelle($value['libelle']);
            $etat->setId($value['id']);
            $manager->persist($etat);

            $this->addReference('cat_'. $key, $etat);
        }
        $manager->flush();


    }

}
