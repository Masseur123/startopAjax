<?php

namespace App\Repository;

use App\Entity\StockWood;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;

/**
 * @method StockWood|null find($id, $lockMode = null, $lockVersion = null)
 * @method StockWood|null findOneBy(array $criteria, array $orderBy = null)
 * @method StockWood[]    findAll()
 * @method StockWood[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StockWoodRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, StockWood::class);
    }

    // /**
    //  * @return StockWood[] Returns an array of StockWood objects
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
    public function findOneBySomeField($value): ?StockWood
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
