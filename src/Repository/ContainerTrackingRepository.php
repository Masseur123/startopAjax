<?php

namespace App\Repository;

use App\Entity\ContainerTracking;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;

/**
 * @method ContainerTracking|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContainerTracking|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContainerTracking[]    findAll()
 * @method ContainerTracking[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContainerTrackingRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ContainerTracking::class);
    }

    // /**
    //  * @return ContainerTracking[] Returns an array of ContainerTracking objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ContainerTracking
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
