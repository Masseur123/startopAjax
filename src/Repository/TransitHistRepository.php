<?php

namespace App\Repository;

use App\Entity\TransitHist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;

/**
 * @method TransitHist|null find($id, $lockMode = null, $lockVersion = null)
 * @method TransitHist|null findOneBy(array $criteria, array $orderBy = null)
 * @method TransitHist[]    findAll()
 * @method TransitHist[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransitHistRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TransitHist::class);
    }

    // /**
    //  * @return TransitHist[] Returns an array of TransitHist objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TransitHist
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
