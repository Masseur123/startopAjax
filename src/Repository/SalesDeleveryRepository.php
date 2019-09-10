<?php

namespace App\Repository;

use App\Entity\SalesDelevery;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;

/**
 * @method SalesDelevery|null find($id, $lockMode = null, $lockVersion = null)
 * @method SalesDelevery|null findOneBy(array $criteria, array $orderBy = null)
 * @method SalesDelevery[]    findAll()
 * @method SalesDelevery[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SalesDeleveryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SalesDelevery::class);
    }

    // /**
    //  * @return SalesDelevery[] Returns an array of SalesDelevery objects
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
    public function findOneBySomeField($value): ?SalesDelevery
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
