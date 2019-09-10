<?php

namespace App\Repository;

use App\Entity\QuotationArticle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;

/**
 * @method QuotationArticle|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuotationArticle|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuotationArticle[]    findAll()
 * @method QuotationArticle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuotationArticleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, QuotationArticle::class);
    }

    // /**
    //  * @return QuotationArticle[] Returns an array of QuotationArticle objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('q.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?QuotationArticle
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
