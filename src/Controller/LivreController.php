<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Livre;
use App\Repository\AuteurRepository;

class LivreController extends AbstractController
{
    #[Route('/livres', name: 'livres_index', methods: ['GET'])]
    public function index(Request $request, EntityManagerInterface $entityManager, AuteurRepository $auteurRepository): Response {
        $filter = $request->query->get('filter');
        $auteurId = $request->query->get('auteurId');
        $minLivres = $request->query->get('minLivres');
        
        // Récupérer les auteurs selon le filtre minLivres
        if ($minLivres) {
            // Récupérer les auteurs ayant publié plus de livres que le nombre spécifié
            $auteurs = $auteurRepository->findAuthorsWithAtLeastNumberOfBooks((int)$minLivres);
        } else {
            // Si aucun filtre n'est spécifié, récupérer tous les auteurs
            $auteurs = $auteurRepository->findAll();
        }

        // Récupérer les livres selon les filtres
        $livres = $this->getLivresByFilters($entityManager, $auteurId, $filter, $auteurs);

        // Récupérer le nombre total de livres
        $nbLivre = $entityManager->getRepository(Livre::class)->countAllBooks();
        
        // Récupérer le nombre de livres affichés
        $nbFilteredLivre = count($livres);
        
        // Récupérer l'auteur sélectionné
        $selectedAuteur = $auteurId ? $auteurRepository->find($auteurId) : null;

        // Récupérer les premières lettres des titres des livres
        $lettres = $entityManager->getRepository(Livre::class)->findFirstLettersOfTitles();

        // Générer les options pour le filtre minLivres
        $maxBooksCounts = $auteurRepository->findMaxBooksCountByAuthors();
        $maxBooks = max(array_column($maxBooksCounts, 'nbrLivres'));
        $bookCountOptions = range(1, $maxBooks > 0 ? $maxBooks - 1 : 0);

        return $this->render('livres/index.html.twig', [
            'livres' => $livres,
            'nbLivre' => $nbLivre,
            'auteurs' => $auteurs,
            'filter' => $filter,
            'nbFilteredLivre' => $nbFilteredLivre,
            'selectedAuteur' => $selectedAuteur,
            'auteurId' => $auteurId,
            'minLivres' => $minLivres,
            'lettres' => $lettres,
            'bookCountOptions' => $bookCountOptions,
        ]);
    }

    #[Route('/livres/detail/{id}', name: 'detail_livre', methods: ['GET'])]
    public function detail(EntityManagerInterface $entityManager, int $id): Response {
        $livre = $entityManager->getRepository(Livre::class)->find($id);

        if (!$livre) {
            throw $this->createNotFoundException("Le livre demandé n'existe pas");
        }

        return $this->render('livres/detail.html.twig', [
            'livre' => $livre,
        ]);
    }

    private function getLivresByFilters(EntityManagerInterface $entityManager, $auteurId, $filter, $auteurs) {
        $queryBuilder = $entityManager->getRepository(Livre::class)->createQueryBuilder('l');
        
        if ($filter) {
            $queryBuilder->andWhere('l.titre LIKE :filter')
                         ->setParameter('filter', $filter.'%');
        }

        if ($auteurId) {
            $queryBuilder->andWhere('l.auteur = :auteurId')
                         ->setParameter('auteurId', $auteurId);
        } elseif ($auteurs) {
            $auteursIds = array_map(function($auteur) { return $auteur->getId(); }, $auteurs);
            $queryBuilder->andWhere('l.auteur IN (:auteursIds)')
                         ->setParameter('auteursIds', $auteursIds);
        }

        return $queryBuilder->getQuery()->getResult();
    }
}
