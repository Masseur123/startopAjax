<?php

namespace App\Repository;

use App\Entity\Containerlength;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;
use Twig_TokenParser_Include;

/**
 * @method Containerlength|null find($id, $lockMode = null, $lockVersion = null)
 * @method Containerlength|null findOneBy(array $criteria, array $orderBy = null)
 * @method Containerlength[]    findAll()
 * @method Containerlength[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContainerlengthRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Containerlength::class);
    }

//    /**
//     * @return Containerlength[] Returns an array of Containerlength objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Containerlength
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
