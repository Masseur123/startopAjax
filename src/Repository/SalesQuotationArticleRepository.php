<?php

namespace App\Repository;

use App\Entity\SalesQuotationArticle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;

/**
 * @method SalesQuotationArticle|null find($id, $lockMode = null, $lockVersion = null)
 * @method SalesQuotationArticle|null findOneBy(array $criteria, array $orderBy = null)
 * @method SalesQuotationArticle[]    findAll()
 * @method SalesQuotationArticle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SalesQuotationArticleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SalesQuotationArticle::class);
    }

    // /**
    //  * @return SalesQuotationArticle[] Returns an array of SalesQuotationArticle objects
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
    public function findOneBySomeField($value): ?SalesQuotationArticle
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
