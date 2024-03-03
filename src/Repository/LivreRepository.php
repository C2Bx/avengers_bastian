<?php

namespace App\Repository;

use App\Entity\Livre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class LivreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livre::class);
    }

    public function countAllBooks(): int
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT COUNT(l.id) as bookCount
            FROM App\Entity\Livre l'
        );

        return (int) $query->getSingleScalarResult();
    }

    public function findFirstLettersOfTitles()
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT DISTINCT SUBSTRING(l.titre, 1, 1) as lettre
            FROM App\Entity\Livre l
            ORDER BY lettre ASC'
        );

        return array_column($query->getResult(), 'lettre');
    }

    public function findByFirstLetter($letter)
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT l
            FROM App\Entity\Livre l
            WHERE l.titre LIKE :letter
            ORDER BY l.titre ASC'
        )->setParameter('letter', $letter.'%');

        return $query->getResult();
    }

    
    public function countBooksByFirstLetter(string $letter): int
    {
        $qb = $this->createQueryBuilder('l');
        $qb->select('COUNT(l.id)');
        $qb->andWhere('SUBSTRING(l.titre, 1, 1) = :letter');
        $qb->setParameter('letter', $letter);

        return (int) $qb->getQuery()->getSingleScalarResult();
    }
}
