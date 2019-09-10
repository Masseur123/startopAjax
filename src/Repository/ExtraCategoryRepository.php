<?php

namespace App\Repository;

use App\Entity\ExtraCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;

/**
 * @method ExtraCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExtraCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExtraCategory[]    findAll()
 * @method ExtraCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExtraCategoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ExtraCategory::class);
    }

//    /**
//     * @return ExtraCategory[] Returns an array of ExtraCategory objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ExtraCategory
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
