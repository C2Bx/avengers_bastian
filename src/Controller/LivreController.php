<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Livre;
use App\Entity\Auteur; 

class LivreController extends AbstractController
{
    #[Route('/livres', name: 'livres_index')]
    public function afficherTable(Request $request, EntityManagerInterface $entityManager): Response {
        // Récupérer les valeurs des filtres depuis la requête
        $auteurId = $request->query->get('auteur');
        $filter = $request->query->get('filter');
        
        // Récupérer la liste des livres selon les filtres
        $livres = $this->getLivresByFilters($entityManager, $auteurId, $filter);

        // Récupérer le nombre total de livres
        $nbLivre = $entityManager->getRepository(Livre::class)->countAllBooks();
        
        // Récupérer la liste des auteurs pour le formulaire
        $auteurs = $entityManager->getRepository(Auteur::class)->findAll();
        
        // Générer les lettres pour le filtre par lettre
        $lettres = range('A', 'Z');

        return $this->render('livres/index.html.twig', [
            'livres' => $livres,
            'nbLivre' => $nbLivre,
            'auteurs' => $auteurs,
            'filter' => $filter,
            'lettres' => $lettres, // Passer les lettres au modèle Twig
        ]);
    }

    #[Route('/livres/detail/{id}', name: 'detail_livre')]
    public function detail(EntityManagerInterface $entityManager, int $id): Response {
        $livre = $entityManager->getRepository(Livre::class)->find($id);

        if (!$livre) {
            throw $this->createNotFoundException("Le livre demandé n'existe pas");
        }

        return $this->render('livres/detail.html.twig', [
            'livre' => $livre,
        ]);
    }

    // Fonction pour récupérer les livres selon les filtres
    private function getLivresByFilters(EntityManagerInterface $entityManager, $auteurId, $filter) {
        $livreRepository = $entityManager->getRepository(Livre::class);
        $criteria = [];

        // Filtrer par auteur si une valeur est fournie
        if ($auteurId) {
            $criteria['auteur'] = $auteurId;
        }

        // Filtrer par lettre si une valeur est fournie
        if ($filter) {
            $criteria['filter'] = $filter;
        }

        // Récupérer les livres selon les critères
        return $livreRepository->findBy($criteria);
    }
}
