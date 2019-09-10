<?php

namespace App\Repository;

use App\Entity\HousingOption;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;
use Twig_TokenParser_With;

/**
 * @method HousingOption|null find($id, $lockMode = null, $lockVersion = null)
 * @method HousingOption|null findOneBy(array $criteria, array $orderBy = null)
 * @method HousingOption[]    findAll()
 * @method HousingOption[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HousingOptionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, HousingOption::class);
    }

//    /**
//     * @return HousingOption[] Returns an array of HousingOption objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?HousingOption
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
