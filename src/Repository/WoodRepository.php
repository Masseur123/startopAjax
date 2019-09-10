<?php

namespace App\Repository;

use App\Entity\Wood;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;

/**
 * @method Wood|null find($id, $lockMode = null, $lockVersion = null)
 * @method Wood|null findOneBy(array $criteria, array $orderBy = null)
 * @method Wood[]    findAll()
 * @method Wood[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WoodRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Wood::class);
    }

//    /**
//     * @return Wood[] Returns an array of Wood objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Wood
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
