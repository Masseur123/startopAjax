<?php

namespace App\Repository;

use App\Entity\CustomPrice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;

/**
 * @method CustomPrice|null find($id, $lockMode = null, $lockVersion = null)
 * @method CustomPrice|null findOneBy(array $criteria, array $orderBy = null)
 * @method CustomPrice[]    findAll()
 * @method CustomPrice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomPriceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CustomPrice::class);
    }

    // /**
    //  * @return CustomPrice[] Returns an array of CustomPrice objects
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
    public function findOneBySomeField($value): ?CustomPrice
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
