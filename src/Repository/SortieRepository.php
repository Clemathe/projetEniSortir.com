<?php

namespace App\Repository;

use App\data\FindSortie;
use App\Entity\Sortie;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    private $security;

    public function __construct(ManagerRegistry $registry,Security $security)
    {
        parent::__construct($registry, Sortie::class);
        $this->security = $security;
    }

    /**
     * @return Sortie[]
     */
    public function findSearch(FindSortie $search): array
    {

        $query = $this->createQueryBuilder('s')
            ->select('l', 's', 'v', 'u')
            ->join('s.lieu', 'l')
            ->join('l.ville', 'v')
            ->join('s.users', 'u');
        //ajout de la zone de recherche dans la requete

        if ($search->getQ() != null || $search->getQ() != '') {
            $query->andWhere('s.name LIKE :nom')
                ->setParameter('nom', '%' . $search->getQ() . '%');
        }

        //ajout de la checkbox du campus dans la requete
        if ($search->getCampus() != '') {
            $query
                ->join('u.campus', 'c')
                ->andWhere('c.name LIKE :campus')
                ->setParameter('campus', '%' . $search->getCampus() . '%');
        }
       //ajout de la date de debut
        if (($search->getStartDate() != null || $search->getStartDate())) {
            $query->andWhere('s.startedDateTime > :dateDebut')
                ->setParameter('dateDebut', $search->getStartDate());
        }
        //ajout de la date de fin
        if (($search->getEndDate() != null || $search->getEndDate())) {
            $query->andWhere('s.deadline < :dateFin')
                ->setParameter('dateFin', $search->getEndDate());
        }
        //Sortie terminée
        if ($search->getOutOfDate()== true){
            $query->andWhere('s.deadline > :dateFin')
                ->setParameter('dateFin',$search->getEndDate());

        }
        // celle que j'organise
        if ($search->createdByMe==true){
            //recherche id User
            /* @var $user User */
            $user = $this->security->getUser();

            //recherche pa id des sorties
            $query->andWhere('s.organiser = :userId')
                ->setParameter('userId', $user->getId());
        }


        if ($query)
            return $query->getQuery()->getResult();
    }
}
