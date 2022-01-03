<?php

namespace App\Repository;

use App\Entity\ProjectItemProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProjectItemProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProjectItemProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProjectItemProduct[]    findAll()
 * @method ProjectItemProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectItemProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjectItemProduct::class);
    }

    // /**
    //  * @return ProjectItemProduct[] Returns an array of ProjectItemProduct objects
    //  */
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
    public function findOneBySomeField($value): ?ProjectItemProduct
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
