<?php

namespace App\Repository;

use App\Entity\ProductFeatureGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProductFeatureGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductFeatureGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductFeatureGroup[]    findAll()
 * @method ProductFeatureGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductFeatureGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductFeatureGroup::class);
    }

    // /**
    //  * @return ProductFeatureGroup[] Returns an array of ProductFeatureGroup objects
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
    public function findOneBySomeField($value): ?ProductFeatureGroup
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
