<?php

namespace App\Repository;

use App\Entity\TmpAccount;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;
use Twig_Parser;

/**
 * @method TmpAccount|null find($id, $lockMode = null, $lockVersion = null)
 * @method TmpAccount|null findOneBy(array $criteria, array $orderBy = null)
 * @method TmpAccount[]    findAll()
 * @method TmpAccount[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TmpAccountRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TmpAccount::class);
    }

    // /**
    //  * @return TmpAccount[] Returns an array of TmpAccount objects
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
    public function findOneBySomeField($value): ?TmpAccount
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
