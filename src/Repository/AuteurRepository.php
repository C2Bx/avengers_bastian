<?php
// src/Repository/AuteurRepository.php
namespace App\Repository;

use App\Entity\Auteur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AuteurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Auteur::class);
    }

    /**
     * Retourne tous les auteurs ayant au moins un livre.
     * 
     * @return Auteur[] Retourne un tableau d'objets Auteur.
     */
    public function findAuteursWithBooks(): array
    {
        $entityManager = $this->getEntityManager();
        $dql = 'SELECT a 
                FROM App\Entity\Auteur a 
                JOIN a.livres l 
                GROUP BY a.id';

        $query = $entityManager->createQuery($dql);
        return $query->getResult();
    }

    /**
     * Retourne les auteurs ayant un nombre minimum de livres.
     * 
     * @param int $number Le nombre minimum de livres qu'un auteur doit avoir.
     * @return Auteur[] Retourne un tableau d'objets Auteur.
     */
    public function findAuthorsWithAtLeastNumberOfBooks(int $number): array
    {
        $qb = $this->createQueryBuilder('a');
        $qb->select('a')
            ->join('a.livres', 'l')
            ->groupBy('a.id')
            ->having('COUNT(l.id) > :number')
            ->setParameter('number', $number);

        return $qb->getQuery()->getResult();
    }

    /**
     * Retourne le nombre maximal de livres par auteur.
     * 
     * @return array Retourne un tableau associatif avec les auteurs et leur nombre de livres.
     */
    public function findMaxBooksCountByAuthors(): array
    {
        return $this->createQueryBuilder('a')
            ->select('a as auteur, COUNT(l.id) as nbrLivres')
            ->join('a.livres', 'l')
            ->groupBy('a.id')
            ->orderBy('nbrLivres', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
