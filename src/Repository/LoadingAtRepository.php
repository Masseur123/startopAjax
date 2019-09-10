<?php

namespace App\Repository;

use App\Entity\LoadingAt;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;

/**
 * @method LoadingAt|null find($id, $lockMode = null, $lockVersion = null)
 * @method LoadingAt|null findOneBy(array $criteria, array $orderBy = null)
 * @method LoadingAt[]    findAll()
 * @method LoadingAt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LoadingAtRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, LoadingAt::class);
    }

//    /**
//     * @return LoadingAt[] Returns an array of LoadingAt objects
//     */
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
    public function findOneBySomeField($value): ?LoadingAt
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
