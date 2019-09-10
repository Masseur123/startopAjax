<?php

namespace App\Repository;

use App\Entity\LogProcurement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;

/**
 * @method LogProcurement|null find($id, $lockMode = null, $lockVersion = null)
 * @method LogProcurement|null findOneBy(array $criteria, array $orderBy = null)
 * @method LogProcurement[]    findAll()
 * @method LogProcurement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LogProcurementRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, LogProcurement::class);
    }

    // /**
    //  * @return LogProcurement[] Returns an array of LogProcurement objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LogProcurement
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
