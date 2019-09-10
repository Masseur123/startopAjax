<?php

namespace App\Repository;

use App\Entity\ShippingLine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;

/**
 * @method ShippingLine|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShippingLine|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShippingLine[]    findAll()
 * @method ShippingLine[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShippingLineRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ShippingLine::class);
    }

//    /**
//     * @return ShippingLine[] Returns an array of ShippingLine objects
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
    public function findOneBySomeField($value): ?ShippingLine
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
