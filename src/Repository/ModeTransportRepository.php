<?php

namespace App\Repository;

use App\Entity\ModeTransport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;
use Twig_TokenParser_Embed;

/**
 * @method ModeTransport|null find($id, $lockMode = null, $lockVersion = null)
 * @method ModeTransport|null findOneBy(array $criteria, array $orderBy = null)
 * @method ModeTransport[]    findAll()
 * @method ModeTransport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModeTransportRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ModeTransport::class);
    }

//    /**
//     * @return ModeTrans[] Returns an array of ModeTrans objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ModeTrans
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
