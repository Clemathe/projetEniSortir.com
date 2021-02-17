<?php

namespace App\Repository;

use App\Entity\FindSortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FindSortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method FindSortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method FindSortie[]    findAll()
 * @method FindSortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FindSortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FindSortie::class);
    }

    // /**
    //  * @return FindSortie[] Returns an array of FindSortie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FindSortie
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
