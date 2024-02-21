<?php

namespace App\Repository;

use App\Entity\Flore;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Flore>
 *
 * @method Flore|null find($id, $lockMode = null, $lockVersion = null)
 * @method Flore|null findOneBy(array $criteria, array $orderBy = null)
 * @method Flore[]    findAll()
 * @method Flore[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FloreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Flore::class);
    }

//    /**
//     * @return Flore[] Returns an array of Flore objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Flore
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
