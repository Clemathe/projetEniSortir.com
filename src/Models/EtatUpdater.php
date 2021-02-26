<?php


namespace App\Models;


use Doctrine\ORM\EntityManagerInterface;

class EtatUpdater
{
    public function miseAJourEtat(EntityManagerInterface $em) : void
    {

        $stmt = $em->getConnection()->prepare("CALL miseAJourEtat()");
        $stmt->execute();

    }
}