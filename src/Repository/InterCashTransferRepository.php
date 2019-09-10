<?php

namespace App\Repository;

use App\Entity\InterCashTransfer;
use App\Entity\CashDesk;
use App\Entity\Bank;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;
use Twig_Sandbox_SecurityNotAllowedMethodError;

/**
 * @method InterCashTransfer|null find($id, $lockMode = null, $lockVersion = null)
 * @method InterCashTransfer|null findOneBy(array $criteria, array $orderBy = null)
 * @method InterCashTransfer[]    findAll()
 * @method InterCashTransfer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InterCashTransferRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InterCashTransfer::class);
    }

    public function totalAmountForAwaitingTransferOfCashdesk(CashDesk $cashdeskSrc)
    {
        return $this->createQueryBuilder('tr')
            ->andWhere('tr.cashdeskSrc = :cashdesk')
            ->andWhere('tr.type = :type')
            ->andWhere('tr.is_valid = :valid')
            ->setParameter('cashdesk', $cashdeskSrc)
            ->setParameter('type', 'cashTOcash')
            ->setParameter('valid', false)
            ->select('SUM(tr.amount) as totalAmount')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function totalAmountForAwaitingTransferOfBank(Bank $bankSrc)
    {
        return $this->createQueryBuilder('tr')
            ->andWhere('tr.bankSrc = :bank')
            ->andWhere('tr.type = :type')
            ->andWhere('tr.is_valid = :valid')
            ->setParameter('bank', $bankSrc)
            ->setParameter('type', 'bankTObank')
            ->setParameter('valid', false)
            ->select('SUM(tr.amount) as totalAmount')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function totalAmountForAwaitingTransferOfBankToCash(Bank $bankSrc)
    {
        return $this->createQueryBuilder('tr')
            ->andWhere('tr.bankSrc = :bank')
            ->andWhere('tr.type = :type')
            ->andWhere('tr.is_valid = :valid')
            ->setParameter('bank', $bankSrc)
            ->setParameter('type', 'bankTOcash')
            ->setParameter('valid', false)
            ->select('SUM(tr.amount) as totalAmount')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function totalAmountForAwaitingTransferOfCashToBank(CashDesk $cashdeskSrc)
    {
        return $this->createQueryBuilder('tr')
            ->andWhere('tr.cashdeskSrc = :cashdesk')
            ->andWhere('tr.type = :type')
            ->andWhere('tr.is_valid = :valid')
            ->setParameter('cashdesk', $cashdeskSrc)
            ->setParameter('type', 'cashTObank')
            ->setParameter('valid', false)
            ->select('SUM(tr.amount) as totalAmount')
            ->getQuery()
            ->getSingleScalarResult();
    }

    // /**
    //  * @return InterCashTransfer[] Returns an array of InterCashTransfer objects
    //  */
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
    public function findOneBySomeField($value): ?InterCashTransfer
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
