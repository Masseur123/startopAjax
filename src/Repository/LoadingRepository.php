<?php

namespace App\Repository;

use App\Entity\Loading;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;

/**
 * @method Loading|null find($id, $lockMode = null, $lockVersion = null)
 * @method Loading|null findOneBy(array $criteria, array $orderBy = null)
 * @method Loading[]    findAll()
 * @method Loading[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LoadingRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Loading::class);
    }

//    /**
//     * @return Loading[] Returns an array of Loading objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Loading
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
