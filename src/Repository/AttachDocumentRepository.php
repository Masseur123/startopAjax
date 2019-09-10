<?php

namespace App\Repository;

use App\Entity\AttachDocument;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;
use Twig_TokenParser_Import;

/**
 * @method AttachDocument|null find($id, $lockMode = null, $lockVersion = null)
 * @method AttachDocument|null findOneBy(array $criteria, array $orderBy = null)
 * @method AttachDocument[]    findAll()
 * @method AttachDocument[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AttachDocumentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AttachDocument::class);
    }

    // /**
    //  * @return AttachDocument[] Returns an array of AttachDocument objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AttachDocument
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
