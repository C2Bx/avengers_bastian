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

    public function findAuteursWithAtLeastNumberOfBooks($number): array
    {
        return $this->createQueryBuilder('a')
            ->innerJoin('a.livres', 'l')
            ->groupBy('a.id')
            ->having('COUNT(l.id) > :number')
            ->setParameter('number', $number)
            ->getQuery()
            ->getResult();
    }
}
