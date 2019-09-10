<?php

namespace App\Repository;

use App\Entity\TagCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;
use Twig_TokenParser_Do;

/**
 * @method TagCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method TagCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method TagCategory[]    findAll()
 * @method TagCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagCategoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TagCategory::class);
    }

//    /**
//     * @return TagCategory[] Returns an array of TagCategory objects
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
    public function findOneBySomeField($value): ?TagCategory
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
