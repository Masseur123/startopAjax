<?php

namespace App\Repository;

use App\Entity\ParamCostCenter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;

/**
 * @method ParamCostCenter|null find($id, $lockMode = null, $lockVersion = null)
 * @method ParamCostCenter|null findOneBy(array $criteria, array $orderBy = null)
 * @method ParamCostCenter[]    findAll()
 * @method ParamCostCenter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParamCostCenterRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ParamCostCenter::class);
    }

//    /**
//     * @return ParamCostCenter[] Returns an array of ParamCostCenter objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ParamCostCenter
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
