<?php

namespace App\Repository;

use App\Entity\CostCenter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;
use Twig_Extension_InitRuntimeInterface;

/**
 * @method CostCenter|null find($id, $lockMode = null, $lockVersion = null)
 * @method CostCenter|null findOneBy(array $criteria, array $orderBy = null)
 * @method CostCenter[]    findAll()
 * @method CostCenter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CostCenterRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CostCenter::class);
    }

    public function costCenterWithAccountConfigured()
    {
        $entityManager = $this->getEntityManager();

        $dql = 'SELECT c FROM App\Entity\CostCenter c, App\Entity\Account a WHERE c.account = a.id ORDER BY c.id DESC';
        //$dql = 'SELECT c FROM App\Entity\CostCenter c ORDER BY c.id DESC';
        $query = $entityManager->createQuery($dql);
        /*var_dump($query->getResult());
        die;*/
        //return $query->execute();
        return $query->getArrayResult();
    }

    public function costCenterAllocated($years)
    {
        // Entity manager to build query then execute it 
        $entityManager = $this->getEntityManager();

        // Get the current open year
        $year = $years->findOneBy(['is_open' => true, 'is_current' => true]);

        $dql = 'SELECT c 
                FROM App\Entity\CostCenter c, App\Entity\ParamCostCenter p 
                WHERE c.id = p.costcenter AND p.year = ' . $year;
        $query = $entityManager->createQuery($dql);
        var_dump($query->execute());
        die;
        return $query->execute();
    }

    //    /**
    //     * @return CostCenter[] Returns an array of CostCenter objects
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
    public function findOneBySomeField($value): ?CostCenter
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
