<?php

namespace App\Repository;

use App\Entity\Pension;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;

/**
 * @method Pension|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pension|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pension[]    findAll()
 * @method Pension[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PensionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Pension::class);
    }

    // /**
    //  * @return Pension[] Returns an array of Pension objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Pension
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
