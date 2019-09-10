<?php

namespace App\Repository;

use App\Entity\CostCenterHist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;

/**
 * @method CostCenterHist|null find($id, $lockMode = null, $lockVersion = null)
 * @method CostCenterHist|null findOneBy(array $criteria, array $orderBy = null)
 * @method CostCenterHist[]    findAll()
 * @method CostCenterHist[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CostCenterHistRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CostCenterHist::class);
    }

    // /**
    //  * @return CostCenterHist[] Returns an array of CostCenterHist objects
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
    public function findOneBySomeField($value): ?CostCenterHist
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
