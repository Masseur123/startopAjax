<?php

namespace App\Repository;

use App\Entity\Exporter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;

/**
 * @method Exporter|null find($id, $lockMode = null, $lockVersion = null)
 * @method Exporter|null findOneBy(array $criteria, array $orderBy = null)
 * @method Exporter[]    findAll()
 * @method Exporter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExporterRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Exporter::class);
    }

//    /**
//     * @return Exporter[] Returns an array of Exporter objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Exporter
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
