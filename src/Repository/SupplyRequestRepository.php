<?php

namespace App\Repository;

use App\Entity\SupplyRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;

/**
 * @method SupplyRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method SupplyRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method SupplyRequest[]    findAll()
 * @method SupplyRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SupplyRequestRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SupplyRequest::class);
    }

//    /**
//     * @return SupplyRequest[] Returns an array of SupplyRequest objects
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
    public function findOneBySomeField($value): ?SupplyRequest
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
