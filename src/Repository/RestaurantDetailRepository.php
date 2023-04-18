<?php

namespace App\Repository;

use App\Entity\RestaurantDetail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RestaurantDetail|null find($id, $lockMode = null, $lockVersion = null)
 * @method RestaurantDetail|null findOneBy(array $criteria, array $orderBy = null)
 * @method RestaurantDetail[]    findAll()
 * @method RestaurantDetail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RestaurantDetailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RestaurantDetail::class);
    }

    // /**
    //  * @return RestaurantDetail[] Returns an array of RestaurantDetail objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    public function findByUserId(int $userId): ?RestaurantDetail
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.user_id = :val')
            ->setParameter('val', $userId)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
