<?php

namespace App\Repository;

use App\Entity\ProjectItemSupplierEstimate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProjectItemSupplierEstimate|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProjectItemSupplierEstimate|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProjectItemSupplierEstimate[]    findAll()
 * @method ProjectItemSupplierEstimate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectItemSupplierEstimateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjectItemSupplierEstimate::class);
    }

    // /**
    //  * @return ProjectItemSupplierEstimate[] Returns an array of ProjectItemSupplierEstimate objects
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
    public function findOneBySomeField($value): ?ProjectItemSupplierEstimate
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
