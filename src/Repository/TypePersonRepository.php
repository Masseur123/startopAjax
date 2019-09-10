<?php

namespace App\Repository;

use App\Entity\TypePerson;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;

/**
 * @method TypePerson|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypePerson|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypePerson[]    findAll()
 * @method TypePerson[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypePersonRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TypePerson::class);
    }

    // /**
    //  * @return TypePerson[] Returns an array of TypePerson objects
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
    public function findOneBySomeField($value): ?TypePerson
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
