<?php

namespace App\Repository;

use App\Entity\Faune;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Faune>
 *
 * @method Faune|null find($id, $lockMode = null, $lockVersion = null)
 * @method Faune|null findOneBy(array $criteria, array $orderBy = null)
 * @method Faune[]    findAll()
 * @method Faune[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FauneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Faune::class);
    }

//    /**
//     * @return Faune[] Returns an array of Faune objects
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

//    public function findOneBySomeField($value): ?Faune
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
