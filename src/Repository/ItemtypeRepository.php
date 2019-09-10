<?php

namespace App\Repository;

use App\Entity\Itemtype;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;

/**
 * @method Itemtype|null find($id, $lockMode = null, $lockVersion = null)
 * @method Itemtype|null findOneBy(array $criteria, array $orderBy = null)
 * @method Itemtype[]    findAll()
 * @method Itemtype[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemtypeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Itemtype::class);
    }

//    /**
//     * @return Itemtype[] Returns an array of Itemtype objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Itemtype
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
