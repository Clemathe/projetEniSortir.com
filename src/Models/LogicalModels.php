<?php


namespace App\Models;

use App\Entity\Etat;
use App\Entity\Sortie;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;


class LogicalModels
{
    /**
     * @param Sortie $sortie
     * @param User $user
     * @param EntityManagerInterface $em
     * @return string[]
     *
     * Test différentes contraintes afin d'accepter ou de refuser une inscription à une sortie
     */
    public function logicalConstraintsToSaveANewRegistration(User $user,
                                                             Sortie $sortie,
                                                             EntityManagerInterface $em): array
    {
        // Si les inscriptions sont ouvertes
        if ($sortie->getEtat()->getLibelle() == 'Ouverte') {

            // Si la date limite pour les inscritptions n'est pas dépassée
            if ($sortie->getDeadline() > new DateTime()) {

                // Si le nombre maximum de participants n'est pas depassé
                if ($sortie->getMaxNbOfRegistration() >= $sortie->getUsers()->count()) {

                    // si l'user n'est pas déjà inscrit
                    if (!$sortie->getUsers()->contains($user)) {

                        $user->addSortie($sortie);
                        $sortie->addUser($user);

                        $em->persist($user);
                        $em->flush();

                        return ['success', 'Vous êtes inscrits'];
                    } else {
                        return ['danger', 'Vous êtes déjà inscrit'];
                    }
                } else {
                    return ['danger', 'Le nombre maximun de participant est déjà atteint'];
                }
            } else {
                return ['danger', 'La date d\'inscription est dépassée'];
            }
        } else {
            return ['danger', 'Les inscriptions ne sont pas ouvertes'];
        }
    }

    /**
     * @param User $user
     * @param Sortie $sortie
     * @param Etat $etat
     * @param EntityManagerInterface $em
     * @return array
     *
     * Test différentes contraintes afin d'accepter ou de refuser l'annulation d'une sortie
     */
    public function logicalConstraintsToCanceledASortie(User $user, Sortie $sortie,
                                                        Etat $etat, EntityManagerInterface $em): array
    {

        // Si l'utilisateur en session est l'organisateur de la sortie
        if ($user->getId() == $sortie->getOrganiser()->getId()) {

            //Si la date de la sortie est déjà dépassé
            if (new DateTime < $sortie->getStartedDateTime()) {


                // passage de la sortie à l'état annulée
                $sortie->setEtat($etat);
                $em->persist($user);
                $em->flush();

                return['success', 'La sortie a bien été annulée'];

            } else {
                return['danger', 'La sortie est terminée ou en cours, impossible de l\'annuler'];
            }
        } else {
            return['danger', 'Vous n\'êtes pas l\'organisateur, impossible de supprimer la sortie'];
        }
    }
}