<?php

namespace App\Repository;

use App\Entity\StayType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;
use Twig_Extension_Profiler;

/**
 * @method StayType|null find($id, $lockMode = null, $lockVersion = null)
 * @method StayType|null findOneBy(array $criteria, array $orderBy = null)
 * @method StayType[]    findAll()
 * @method StayType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StayTypeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, StayType::class);
    }

//    /**
//     * @return StayType[] Returns an array of StayType objects
//     */
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
    public function findOneBySomeField($value): ?StayType
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
