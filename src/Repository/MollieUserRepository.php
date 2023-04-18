<?php

namespace App\Repository;

use App\Entity\MollieUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MollieUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method MollieUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method MollieUser[]    findAll()
 * @method MollieUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MollieUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MollieUser::class);
    }

    // /**
    //  * @return MollieUser[] Returns an array of MollieUser objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MollieUser
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function persist(MollieUser $mollieUser)
    {
        $this->_em->persist($mollieUser);
        $this->_em->flush();
    }
}
