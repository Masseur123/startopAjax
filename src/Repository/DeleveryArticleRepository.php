<?php

namespace App\Repository;

use App\Entity\DeleveryArticle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;

/**
 * @method DeleveryArticle|null find($id, $lockMode = null, $lockVersion = null)
 * @method DeleveryArticle|null findOneBy(array $criteria, array $orderBy = null)
 * @method DeleveryArticle[]    findAll()
 * @method DeleveryArticle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeleveryArticleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DeleveryArticle::class);
    }

    // /**
    //  * @return DeleveryArticle[] Returns an array of DeleveryArticle objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DeleveryArticle
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
