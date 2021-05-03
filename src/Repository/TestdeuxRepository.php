<?php

namespace App\Repository;

use App\Entity\Testdeux;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Testdeux|null find($id, $lockMode = null, $lockVersion = null)
 * @method Testdeux|null findOneBy(array $criteria, array $orderBy = null)
 * @method Testdeux[]    findAll()
 * @method Testdeux[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TestdeuxRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Testdeux::class);
    }

    // /**
    //  * @return Testdeux[] Returns an array of Testdeux objects
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
    public function findOneBySomeField($value): ?Testdeux
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
