<?php

namespace App\Repository;

use App\Entity\LoadingLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;
use Twig_Filter;

/**
 * @method LoadingLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method LoadingLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method LoadingLog[]    findAll()
 * @method LoadingLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LoadingLogRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, LoadingLog::class);
    }

    // /**
    //  * @return LoadingLog[] Returns an array of LoadingLog objects
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
    public function findOneBySomeField($value): ?LoadingLog
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
