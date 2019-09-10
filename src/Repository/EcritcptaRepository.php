<?php

namespace App\Repository;

use App\Entity\Ecritcpta;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;

/**
 * @method Ecritcpta|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ecritcpta|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ecritcpta[]    findAll()
 * @method Ecritcpta[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EcritcptaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Ecritcpta::class);
    }

    // /**
    //  * @return Ecritcpta[] Returns an array of Ecritcpta objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Gledger
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
