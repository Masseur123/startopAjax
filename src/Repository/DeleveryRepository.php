<?php

namespace App\Repository;

use App\Entity\Delevery;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;

/**
 * @method Delevery|null find($id, $lockMode = null, $lockVersion = null)
 * @method Delevery|null findOneBy(array $criteria, array $orderBy = null)
 * @method Delevery[]    findAll()
 * @method Delevery[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeleveryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Delevery::class);
    }

//    /**
//     * @return Delevery[] Returns an array of Delevery objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Delevery
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
