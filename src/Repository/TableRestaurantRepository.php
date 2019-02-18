<?php

namespace App\Repository;

use App\Entity\TableRestaurant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TableRestaurant|null find($id, $lockMode = null, $lockVersion = null)
 * @method TableRestaurant|null findOneBy(array $criteria, array $orderBy = null)
 * @method TableRestaurant[]    findAll()
 * @method TableRestaurant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TableRestaurantRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TableRestaurant::class);
    }

    // /**
    //  * @return TableRestaurant[] Returns an array of TableRestaurant objects
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
    public function findOneBySomeField($value): ?TableRestaurant
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
