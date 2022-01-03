<?php

namespace App\Repository;

use App\Entity\ProjectItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProjectItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProjectItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProjectItem[]    findAll()
 * @method ProjectItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjectItem::class);
    }

    // /**
    //  * @return ProjectItem[] Returns an array of ProjectItem objects
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
    public function findOneBySomeField($value): ?ProjectItem
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
