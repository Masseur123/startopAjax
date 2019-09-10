<?php

namespace App\Repository;

use App\Entity\GeneralArea;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;

/**
 * @method GeneralArea|null find($id, $lockMode = null, $lockVersion = null)
 * @method GeneralArea|null findOneBy(array $criteria, array $orderBy = null)
 * @method GeneralArea[]    findAll()
 * @method GeneralArea[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GeneralAreaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GeneralArea::class);
    }

    // /**
    //  * @return GeneralArea[] Returns an array of GeneralArea objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GeneralArea
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
