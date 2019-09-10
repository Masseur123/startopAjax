<?php

namespace App\Repository;

use App\Entity\GroupComponentRole;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;
use Twig_TokenStream;

/**
 * @method GroupComponentRole|null find($id, $lockMode = null, $lockVersion = null)
 * @method GroupComponentRole|null findOneBy(array $criteria, array $orderBy = null)
 * @method GroupComponentRole[]    findAll()
 * @method GroupComponentRole[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupComponentRoleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GroupComponentRole::class);
    }
	
	public function findByGroupAndComponent($group, $component)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.usergroup = :group')
			->andWhere('g.component = :component')
            ->setParameter('group', $group)
			->setParameter('component', $component)
            ->orderBy('g.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return GroupComponentRole[] Returns an array of GroupComponentRole objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GroupComponentRole
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
