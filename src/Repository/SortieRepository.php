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
     * methodes de recherche du champ findForm sur l'acceuil
     * @return PaginationInterface
     */
    public function findSearch(FindSortie $search): PaginationInterface
    {

        $query = $this->createQueryBuilder('s')
            ->select('l', 's', 'v', 'u')
            ->join('s.lieu', 'l')
            ->join('l.ville', 'v')
            ->join('s.users', 'u');

        // filtre par etat archive et non publié
        $query->andWhere('s.etat NOT IN (1,6,7)');

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
        // Sortie que j'ai organisé
        if ($search->createdByMe == true) {
            //recherche id User
            /* @var $user User */
            $user = $this->security->getUser();
            //recherche par id des sorties
            $query->andWhere('s.organiser = :userId')
                ->setParameter('userId', $user->getId());
        }

        //
        if ($search->subscrided == true) {
            //u.id == moi
            /* @var $user User */
            $user = $this->security->getUser();
            $query->andWhere('u.id = :userId')
                ->setParameter('userId', $user->getId());

        }

        if ($search->unSubscrided == true) {

            /* @var $user User */
            $user = $this->security->getUser();

            $query->andWhere($query->expr()->notIn('u.id', ':userId'))
                ->setParameter('userId', $user->getId());


        }
        $query = $query->getQuery();
        return $this->paginator->paginate($query, $search->page, 12);

        // decommenter si retrait du paginator
        // return $query->getQuery()->getResult();
    }


    /**
     *  Afin d'afficher les sorties des user dans leur profil
     */
    public function getSortiesUser($id = null)
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
            $query->andWhere('u.id = :userId')
                ->setParameter('userId', $user->getId());
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
