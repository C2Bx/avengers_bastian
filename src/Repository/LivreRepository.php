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

    // Méthode pour compter le nombre total de livres
    public function countAllBooks(): int
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT COUNT(l.id) as bookCount
            FROM App\Entity\Livre l'
        );

        return (int) $query->getSingleScalarResult();
    }

    // Méthode pour trouver les premières lettres des titres des livres
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

    // Méthode pour filtrer les livres par la première lettre du titre
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

    // Méthode pour compter le nombre de livres commençant par une certaine lettre
    public function countBooksByFirstLetter(string $letter): int
    {
        $qb = $this->createQueryBuilder('l');
        $qb->select('COUNT(l.id)');
        $qb->andWhere('SUBSTRING(l.titre, 1, 1) = :letter');
        $qb->setParameter('letter', $letter);

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    // Méthode pour compter le nombre total d'auteurs
    public function countAllAuthors(): int
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT COUNT(a.id) as authorCount
            FROM App\Entity\Auteur a'
        );

        return (int) $query->getSingleScalarResult();
    }

    // Méthode pour trouver les premières lettres des noms des auteurs
    public function findFirstLettersOfAuthors()
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT DISTINCT SUBSTRING(a.nom, 1, 1) as lettre
            FROM App\Entity\Auteur a
            ORDER BY lettre ASC'
        );

        return array_column($query->getResult(), 'lettre');
    }

    // Méthode pour filtrer les auteurs par la première lettre de leur nom
    public function findAuthorsByFirstLetter($letter)
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT a
            FROM App\Entity\Auteur a
            WHERE a.nom LIKE :letter
            ORDER BY a.nom ASC'
        )->setParameter('letter', $letter.'%');

        return $query->getResult();
    }
}
