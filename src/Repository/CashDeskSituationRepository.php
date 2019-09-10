<?php

namespace App\Repository;

use App\Entity\CashDeskSituation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;
use Twig_Template;

/**
 * @method CashDeskSituation|null find($id, $lockMode = null, $lockVersion = null)
 * @method CashDeskSituation|null findOneBy(array $criteria, array $orderBy = null)
 * @method CashDeskSituation[]    findAll()
 * @method CashDeskSituation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CashDeskSituationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CashDeskSituation::class);
    }

    // /**
    //  * @return CashDeskSituation[] Returns an array of CashDeskSituation objects
    //  */
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
    public function findOneBySomeField($value): ?CashDeskSituation
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
