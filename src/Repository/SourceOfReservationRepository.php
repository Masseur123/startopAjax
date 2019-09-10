<?php

namespace App\Repository;

use App\Entity\SourceOfReservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;

/**
 * @method SourceOfReservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method SourceOfReservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method SourceOfReservation[]    findAll()
 * @method SourceOfReservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SourceOfReservationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SourceOfReservation::class);
    }

//    /**
//     * @return SourceOfReservation[] Returns an array of SourceOfReservation objects
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
    public function findOneBySomeField($value): ?SourceOfReservation
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
