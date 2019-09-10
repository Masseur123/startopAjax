<?php

namespace App\Repository;

use App\Entity\ReservationGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;
use Twig_Compiler;

/**
 * @method ReservationGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReservationGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReservationGroup[]    findAll()
 * @method ReservationGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationGroupRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ReservationGroup::class);
    }

//    /**
//     * @return ReservationGroup[] Returns an array of ReservationGroup objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ReservationGroup
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
