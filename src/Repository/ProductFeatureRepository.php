<?php

namespace App\Repository;

use App\Entity\ProductFeature;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProductFeature|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductFeature|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductFeature[]    findAll()
 * @method ProductFeature[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductFeatureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductFeature::class);
    }

    // /**
    //  * @return ProductFeature[] Returns an array of ProductFeature objects
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
    public function findOneBySomeField($value): ?ProductFeature
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
