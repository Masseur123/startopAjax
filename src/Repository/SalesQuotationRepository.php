<?php

namespace App\Repository;

use App\Entity\SalesQuotation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;
use Twig_TokenParser_Sandbox;

/**
 * @method SalesQuotation|null find($id, $lockMode = null, $lockVersion = null)
 * @method SalesQuotation|null findOneBy(array $criteria, array $orderBy = null)
 * @method SalesQuotation[]    findAll()
 * @method SalesQuotation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SalesQuotationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SalesQuotation::class);
    }

    // /**
    //  * @return SalesQuotation[] Returns an array of SalesQuotation objects
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
    public function findOneBySomeField($value): ?SalesQuotation
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
