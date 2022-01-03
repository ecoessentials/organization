<?php

namespace App\Repository;

use App\Entity\ProjectItemModel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProjectItemModel|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProjectItemModel|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProjectItemModel[]    findAll()
 * @method ProjectItemModel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectItemModelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjectItemModel::class);
    }

    // /**
    //  * @return ProjectItemModel[] Returns an array of ProjectItemModel objects
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
    public function findOneBySomeField($value): ?ProjectItemModel
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
