<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Livre;
use App\Entity\Auteur;
use App\Repository\AuteurRepository;

class LivreController extends AbstractController
{
    #[Route('/livres', name: 'livres_index', methods: ['GET'])]
    public function index(Request $request, EntityManagerInterface $entityManager, AuteurRepository $auteurRepository): Response {
        $auteurs = $auteurRepository->findAll();
        $filter = $request->query->get('filter');
        $auteurId = $request->query->get('auteurId');
        
        // Récupérer tous les livres ou les livres filtrés selon le cas
        $livres = $this->getLivresByFilters($entityManager, $auteurId, $filter);
        
        // Compter le nombre total de livres
        $nbLivre = $entityManager->getRepository(Livre::class)->countAllBooks();
        
        // Si un auteur est sélectionné, calculer le nombre de livres de cet auteur
        if ($auteurId) {
            $selectedAuteur = $auteurRepository->find($auteurId);
            $nbFilteredLivre = count($selectedAuteur->getLivresByFirstLetter($filter));
        } else {
            // Sinon, si un filtre est appliqué, compter les livres filtrés
            if ($filter) {
                $nbFilteredLivre = $entityManager->getRepository(Livre::class)->countBooksByFirstLetter($filter);
            } else {
                // Sinon, le nombre de livres filtrés est égal au nombre total de livres
                $nbFilteredLivre = $nbLivre;
            }
            $selectedAuteur = null;
        }

        // Trouver les premières lettres des titres des livres
        $lettres = $entityManager->getRepository(Livre::class)->findFirstLettersOfTitles();

        return $this->render('livres/index.html.twig', [
            'livres' => $livres,
            'nbLivre' => $nbLivre,
            'auteurs' => $auteurs,
            'filter' => $filter,
            'nbFilteredLivre' => $nbFilteredLivre,
            'selectedAuteur' => $selectedAuteur,
            'auteurId' => $auteurId,
            'lettres' => $lettres,
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

    private function getLivresByFilters(EntityManagerInterface $entityManager, $auteurId, $filter) {
        if ($filter && $auteurId) {
            // Récupérer les livres de l'auteur sélectionné commençant par la lettre sélectionnée
            $auteur = $entityManager->getRepository(Auteur::class)->find($auteurId);
            return $auteur->getLivresByFirstLetter($filter);
        } elseif ($filter) {
            // Récupérer les livres commençant par la lettre sélectionnée
            return $entityManager->getRepository(Livre::class)->findByFirstLetter($filter);
        } elseif ($auteurId) {
            // Récupérer tous les livres de l'auteur sélectionné
            $auteur = $entityManager->getRepository(Auteur::class)->find($auteurId);
            return $auteur->getLivres();
        } else {
            // Récupérer tous les livres si aucun filtre n'est appliqué
            return $entityManager->getRepository(Livre::class)->findAll();
        }
    }
}
