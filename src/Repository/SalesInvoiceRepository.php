<?php

namespace App\Repository;

use App\Entity\SalesInvoice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;

/**
 * @method SalesInvoice|null find($id, $lockMode = null, $lockVersion = null)
 * @method SalesInvoice|null findOneBy(array $criteria, array $orderBy = null)
 * @method SalesInvoice[]    findAll()
 * @method SalesInvoice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SalesInvoiceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SalesInvoice::class);
    }

    // /**
    //  * @return SalesInvoice[] Returns an array of SalesInvoice objects
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
    public function findOneBySomeField($value): ?SalesInvoice
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
