<?php

namespace App\Repository;

use App\Entity\RestrictionConfiguration;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;

/**
 * @method RestrictionConfiguration|null find($id, $lockMode = null, $lockVersion = null)
 * @method RestrictionConfiguration|null findOneBy(array $criteria, array $orderBy = null)
 * @method RestrictionConfiguration[]    findAll()
 * @method RestrictionConfiguration[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RestrictionConfigurationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RestrictionConfiguration::class);
    }

    // /**
    //  * @return RestrictionConfiguration[] Returns an array of RestrictionConfiguration objects
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
    public function findOneBySomeField($value): ?RestrictionConfiguration
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
