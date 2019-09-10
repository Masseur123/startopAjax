<?php

namespace App\Repository;

use App\Entity\Transit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;

/**
 * @method Transit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Transit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Transit[]    findAll()
 * @method Transit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransitRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Transit::class);
    }

    public function findByIsOpen($value)
    {
        return $this->createQueryBuilder('t')
            ->select('count(t.id)')
            ->andWhere('t.is_open = :isopen')
            ->setParameter('isopen', $value)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findByJoin()
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.id', 'DESC')
            ->setMaxResults(4)
            ->getQuery()
            ->getResult();
    }


    //    /**
    //     * @return Transit[] Returns an array of Transit objects
    //     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
     */

    /*
    public function findOneBySomeField($value): ?Transit
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
     */
}
