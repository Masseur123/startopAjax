<?php

namespace App\Repository;

use App\Entity\TagPlanConfiguration;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;

/**
 * @method TagPlanConfiguration|null find($id, $lockMode = null, $lockVersion = null)
 * @method TagPlanConfiguration|null findOneBy(array $criteria, array $orderBy = null)
 * @method TagPlanConfiguration[]    findAll()
 * @method TagPlanConfiguration[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagPlanConfigurationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TagPlanConfiguration::class);
    }

    // /**
    //  * @return TagPlanConfiguration[] Returns an array of TagPlanConfiguration objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TagPlanConfiguration
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
