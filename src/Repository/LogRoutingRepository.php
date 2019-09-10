<?php

namespace App\Repository;

use App\Entity\LogRouting;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;

/**
 * @method LogRouting|null find($id, $lockMode = null, $lockVersion = null)
 * @method LogRouting|null findOneBy(array $criteria, array $orderBy = null)
 * @method LogRouting[]    findAll()
 * @method LogRouting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LogRoutingRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, LogRouting::class);
    }

    // /**
    //  * @return LogRouting[] Returns an array of LogRouting objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LogRouting
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
