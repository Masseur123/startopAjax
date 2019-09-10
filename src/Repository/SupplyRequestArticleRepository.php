<?php

namespace App\Repository;

use App\Entity\SupplyRequestArticle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;

/**
 * @method SupplyRequestArticle|null find($id, $lockMode = null, $lockVersion = null)
 * @method SupplyRequestArticle|null findOneBy(array $criteria, array $orderBy = null)
 * @method SupplyRequestArticle[]    findAll()
 * @method SupplyRequestArticle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SupplyRequestArticleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SupplyRequestArticle::class);
    }

//    /**
//     * @return SupplyRequestArticle[] Returns an array of SupplyRequestArticle objects
//     */
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
    public function findOneBySomeField($value): ?SupplyRequestArticle
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
