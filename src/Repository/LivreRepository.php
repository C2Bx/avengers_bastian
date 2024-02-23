<?php

// src/Repository/LivreRepository.php
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

    public function findAuthorsWithMoreBooksThan($numberOfBooks): array
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT a.nom, COUNT(l.id) AS bookCount
            FROM App\Entity\Auteur a
            JOIN a.livres l
            GROUP BY a.nom
            HAVING COUNT(l.id) > :numberOfBooks'
        )->setParameter('numberOfBooks', $numberOfBooks);

        return $query->getResult();
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
}
