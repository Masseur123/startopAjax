<?php

namespace App\Repository;

use App\Entity\Person;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;

/**
 * @method Person|null find($id, $lockMode = null, $lockVersion = null)
 * @method Person|null findOneBy(array $criteria, array $orderBy = null)
 * @method Person[]    findAll()
 * @method Person[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Person::class);
    }

    public function findShippingLineAsCompany()
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT s FROM App\Entity\Person p, App\Entity\ShippingLine s WHERE p.id = s.person ORDER BY p.id DESC'
        );
        return $query->execute();
    }

    public function findExporterAsCompany()
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT e FROM App\Entity\Person p, App\Entity\Exporter e WHERE p.id = e.person ORDER BY p.id DESC'
        );
        return $query->execute();
    }

    public function findCarrierAsCompany()
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT c FROM App\Entity\Person p, App\Entity\Carrier c WHERE p.id = c.person ORDER BY p.id DESC'
        );
        return $query->execute();
    }

    public function findPeopleAsCustomer()
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT p FROM App\Entity\Person p, App\Entity\Customer c WHERE p.id = c.person ORDER BY p.id DESC'
        );
        return $query->execute();
    }

    public function findPeopleAsProvider()
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT p FROM App\Entity\Person p, App\Entity\Provider c WHERE p.id = c.person ORDER BY p.id DESC'
        );
        return $query->execute();
    }

//    /**
//     * @return Person[] Returns an array of Person objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Person
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
