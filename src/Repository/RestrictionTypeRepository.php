<?php

namespace App\Repository;

use App\Entity\RestrictionType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;

/**
 * @method RestrictionType|null find($id, $lockMode = null, $lockVersion = null)
 * @method RestrictionType|null findOneBy(array $criteria, array $orderBy = null)
 * @method RestrictionType[]    findAll()
 * @method RestrictionType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RestrictionTypeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RestrictionType::class);
    }

    // /**
    //  * @return RestrictionType[] Returns an array of RestrictionType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RestrictionType
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
