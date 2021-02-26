<?php

namespace App\Repository;

use App\data\FindSortie;
use App\Entity\Sortie;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
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
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    public function __construct(ManagerRegistry $registry, Security $security, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Sortie::class);
        $this->security = $security;
        $this->paginator = $paginator;
    }

    /**
     * @return PaginationInterface
     */
    public function findSearch(FindSortie $search): PaginationInterface
    {

        $query = $this->createQueryBuilder('s')
            ->select('l', 's', 'v')
            ->join('s.lieu', 'l')
            ->join('l.ville', 'v');



        //ajout de la zone de recherche dans la requete
        if ($search->getQ() != null || $search->getQ() != '') {
            $query->andWhere('s.etat NOT IN (1,6,7)')->andWhere('s.name LIKE :nom')
                ->setParameter('nom', '%' . $search->getQ() . '%');
        }

        //ajout de la checkbox du campus dans la requete
        if ($search->getCampus() != '') {
            $query
                ->join('s.users', 'u')
                ->join('u.campus', 'c')
                ->andWhere('s.etat NOT IN (1,6,7)')
                ->andWhere('c.name LIKE :campus')
                ->setParameter('campus', '%' . $search->getCampus() . '%');
        }
        //ajout de la date de debut
        if (($search->getStartDate() != null || $search->getStartDate())) {
            $query->andWhere('s.startedDateTime > :dateDebut')->andWhere('s.etat NOT IN (1,6,7)')
                ->setParameter('dateDebut', $search->getStartDate());
        }
        //ajout de la date de fin
        if (($search->getEndDate() != null || $search->getEndDate())) {
            $query->andWhere('s.deadline < :dateFin')->andWhere('s.etat NOT IN (1,6,7)')
                ->setParameter('dateFin', $search->getEndDate());
        }
        // Sortie que j'ai organisé
        if ($search->createdByMe == true) {
            //recherche id User
            /* @var $user User */
            $user = $this->security->getUser();
            //recherche par id des sorties
            $query->andWhere('s.organiser = :userId')
                ->setParameter('userId', $user->getId());
        }

        // Mes inscriptions
        if ($search->subscrided == true) {
            //u.id == moi
            /* @var $user User */
            $user = $this->security->getUser();
            $query->join('s.users', 'u')
                ->andWhere('u.id = :userId')
                ->setParameter('userId', $user->getId());

        }

        if ($search->unSubscrided == true) {

            /* @var $user User */
            $user = $this->security->getUser();

            $query->join('s.users', 'u')
                ->andWhere($query->expr()->notIn('u.id', ':userId'))
                ->setParameter('userId', $user->getId());

            // $sql= "SELECT id, campus_id, organiser_id, lieu_id, etat_id, name, started_date_time, duration, deadline,
            // max_nb_of_registration, description FROM sortie as s JOIN user_sortie as us ON s.id = us.sortie_id WHERE user_id not in ( 202)";

        }

        $query = $query->getQuery();
        return $this->paginator->paginate($query, $search->page, 12);

        // decommenter si retrait du paginator
        // return $query->getQuery()->getResult();
    }


    /**
     *  Affiche les sorties des users dans leur profil
     */
    public function getSortiesUser($id = null): array
    {
        $query = $this->createQueryBuilder('s')
            ->select('l', 's', 'v', 'u')
            ->join('s.lieu', 'l')
            ->join('l.ville', 'v')
            ->join('s.users', 'u');

        //Si la fonction contient un id en parametre ( pour les autres utilisateurs en bdd)
        if (isset($id)) {

            $query->andWhere('u.id = :Id')
                ->setParameter('Id', $id);

        // Pour l'utilisateur connecté en session
        } else {
            /* @var $user User */
            $user = $this->security->getUser();
            if ($user) {
                $query->andWhere('u.id = :userId')
                    ->setParameter('userId', $user->getId());
            }
        }
        return $query->getQuery()->getResult();

    }
    // function pour la recherche autocompletion dans la search bar
    public function searchQ($search){
        $query = $this->createQueryBuilder('s');
            $query->andWhere('s.name LIKE :nom')
                ->setParameter('nom', '%' . $search->getQ() . '%')
                ->json_encode($query);

        return $query->getQuery()->getResult();

    }

}
