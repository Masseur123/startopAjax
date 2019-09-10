<?php

namespace App\Repository;

use App\Entity\CashDesk;
use App\Entity\Account;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;

/**
 * @method CashDesk|null find($id, $lockMode = null, $lockVersion = null)
 * @method CashDesk|null findOneBy(array $criteria, array $orderBy = null)
 * @method CashDesk[]    findAll()
 * @method CashDesk[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CashDeskRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CashDesk::class);
    }

    public function findOneCashDeskByAccount(Account $account)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.account = :val')
            ->setParameter('val', $account)
            ->getQuery()
            ->getOneOrNullResult();
    }

    //    /**
    //     * @return CashDesk[] Returns an array of CashDesk objects
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
    public function findOneBySomeField($value): ?CashDesk
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
