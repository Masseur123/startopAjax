<?php

namespace App\Repository;

use App\Entity\CostCenterVariation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;

/**
 * @method CostCenterVariation|null find($id, $lockMode = null, $lockVersion = null)
 * @method CostCenterVariation|null findOneBy(array $criteria, array $orderBy = null)
 * @method CostCenterVariation[]    findAll()
 * @method CostCenterVariation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CostCenterVariationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CostCenterVariation::class);
    }

//    /**
//     * @return CostCenterVariation[] Returns an array of CostCenterVariation objects
//     */
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
    public function findOneBySomeField($value): ?CostCenterVariation
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
