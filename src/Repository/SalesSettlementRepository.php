<?php

namespace App\Repository;

use App\Entity\SalesSettlement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;

/**
 * @method SalesSettlement|null find($id, $lockMode = null, $lockVersion = null)
 * @method SalesSettlement|null findOneBy(array $criteria, array $orderBy = null)
 * @method SalesSettlement[]    findAll()
 * @method SalesSettlement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SalesSettlementRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SalesSettlement::class);
    }

    // /**
    //  * @return SalesSettlement[] Returns an array of SalesSettlement objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SalesSettlement
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
