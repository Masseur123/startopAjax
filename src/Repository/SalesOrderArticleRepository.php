<?php

namespace App\Repository;

use App\Entity\SalesOrderArticle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;

/**
 * @method SalesOrderArticle|null find($id, $lockMode = null, $lockVersion = null)
 * @method SalesOrderArticle|null findOneBy(array $criteria, array $orderBy = null)
 * @method SalesOrderArticle[]    findAll()
 * @method SalesOrderArticle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SalesOrderArticleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SalesOrderArticle::class);
    }

    // /**
    //  * @return SalesOrderArticle[] Returns an array of SalesOrderArticle objects
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
    public function findOneBySomeField($value): ?SalesOrderArticle
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
