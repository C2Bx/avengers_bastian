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
    public function findAuteursWithAtLeastNumberOfBooks(int $number): array
    {
        $entityManager = $this->getEntityManager();
        $dql = "SELECT a, COUNT(l.id) as HIDDEN nbrLivres
                FROM App\Entity\Auteur a
                JOIN a.livres l
                GROUP BY a.id
                HAVING COUNT(l.id) > :number";
        
        $query = $entityManager->createQuery($dql)
                    ->setParameter('number', $number);

        return $query->getResult();
    }
}
