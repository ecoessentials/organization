<?php

namespace App\Repository;

use App\Entity\ThirdParty;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ThirdParty|null find($id, $lockMode = null, $lockVersion = null)
 * @method ThirdParty|null findOneBy(array $criteria, array $orderBy = null)
 * @method ThirdParty[]    findAll()
 * @method ThirdParty[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ThirdPartyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ThirdParty::class);
    }

    // /**
    //  * @return ThirdParty[] Returns an array of ThirdParty objects
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
    public function findOneBySomeField($value): ?ThirdParty
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
