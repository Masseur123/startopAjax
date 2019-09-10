<?php

namespace App\Repository;

use App\Entity\DatePeriod;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;

/**
 * @method DatePeriod|null find($id, $lockMode = null, $lockVersion = null)
 * @method DatePeriod|null findOneBy(array $criteria, array $orderBy = null)
 * @method DatePeriod[]    findAll()
 * @method DatePeriod[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DatePeriodRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DatePeriod::class);
    }

    // /**
    //  * @return DatePeriod[] Returns an array of DatePeriod objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DatePeriod
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
