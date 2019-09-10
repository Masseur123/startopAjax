<?php

namespace App\Repository;

use App\Entity\BusinessType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;

/**
 * @method BusinessType|null find($id, $lockMode = null, $lockVersion = null)
 * @method BusinessType|null findOneBy(array $criteria, array $orderBy = null)
 * @method BusinessType[]    findAll()
 * @method BusinessType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BusinessTypeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, BusinessType::class);
    }

    // /**
    //  * @return BusinessType[] Returns an array of BusinessType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BusinessType
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
