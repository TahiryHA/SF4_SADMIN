<?php

namespace App\Repository;

use App\Entity\Folders;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Folders|null find($id, $lockMode = null, $lockVersion = null)
 * @method Folders|null findOneBy(array $criteria, array $orderBy = null)
 * @method Folders[]    findAll()
 * @method Folders[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FoldersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Folders::class);
    }

    // /**
    //  * @return Folders[] Returns an array of Folders objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Folders
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
