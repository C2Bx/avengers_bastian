<?php

namespace App\Repository;

use App\Entity\MarquePage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MarquePage>
 *
 * @method MarquePage|null find($id, $lockMode = null, $lockVersion = null)
 * @method MarquePage|null findOneBy(array $criteria, array $orderBy = null)
 * @method MarquePage[]    findAll()
 * @method MarquePage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MarquePageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MarquePage::class);
    }

//    /**
//     * @return MarquePage[] Returns an array of MarquePage objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?MarquePage
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
