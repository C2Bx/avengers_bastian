<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AuteurRepository;
use App\Repository\LivreRepository;
use App\Entity\Livre;
use App\Entity\Auteur;
use App\Form\Type\LivreType;

class LivreController extends AbstractController
{
    #[Route('/{_locale}/livres', name: 'livres_index', methods: ['GET'])]
    public function index(Request $request, EntityManagerInterface $entityManager, AuteurRepository $auteurRepository, LivreRepository $livreRepository): Response
    {
        $filter = $request->query->get('filter');
        $auteurId = $request->query->getInt('auteurId');
        $minLivres = $request->query->getInt('minLivres');
        $sortBy = $request->query->get('sortBy', 'titre');
        $sortOrder = $request->query->get('sortOrder', 'asc');
        $firstLetter = $request->query->get('firstLetter');

        $livres = $livreRepository->findAll();

        if ($auteurId) {
            $auteur = $auteurRepository->find($auteurId);
            $livres = array_filter($livres, function ($livre) use ($auteur) {
                return $livre->getAuteur() === $auteur;
            });
        }

        if ($filter && $firstLetter) {
            $livres = $livreRepository->findByFirstLetter($firstLetter);
        } elseif ($filter) {
            $livres = array_filter($livres, function ($livre) use ($filter) {
                return stripos($livre->getTitre(), $filter) === 0;
            });
        }

        if ($minLivres) {
            $livres = array_filter($livres, function ($livre) use ($minLivres) {
                $auteur = $livre->getAuteur();
                if ($auteur !== null) {
                    return count($auteur->getLivres()) > $minLivres;
                }
                return false;
            });
        }

        usort($livres, function ($a, $b) use ($sortBy, $sortOrder) {
            $method = 'get' . ucfirst($sortBy);
            $aValue = $a->$method();
            $bValue = $b->$method();
            if ($sortOrder === 'asc') {
                return $aValue <=> $bValue;
            } else {
                return $bValue <=> $aValue;
            }
        });

        $nbFilteredLivre = count($livres);
        $auteurs = $auteurRepository->findAll();
        $nbLivre = $livreRepository->countAllBooks();
        $selectedAuteur = $auteurId ? $auteurRepository->find($auteurId) : null;
        $lettres = $livreRepository->findFirstLettersOfTitles();
        $maxBooksCounts = $auteurRepository->findMaxBooksCountByAuthors();
        $booksCounts = array_column($maxBooksCounts, 'nbrLivres');
        $maxBooks = !empty($booksCounts) ? max($booksCounts) : 0;
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
            'sortOrder' => $sortOrder,
        ]);
    }

    #[Route('/{_locale}/livres/detail/{id}', name: 'detail_livre', methods: ['GET'])]
    public function detail(EntityManagerInterface $entityManager, int $id): Response
    {
        $livre = $entityManager->getRepository(Livre::class)->find($id);

        if (!$livre) {
            throw $this->createNotFoundException("Le livre demandé n'existe pas.");
        }

        return $this->render('livres/detail.html.twig', [
            'livre' => $livre,
        ]);
    }

    #[Route('/{_locale}/livres/ajout', name: 'livre_ajout')]
    public function ajout(Request $request, EntityManagerInterface $entityManager): Response
    {
        $livre = new Livre();
        $form = $this->createForm(LivreType::class, $livre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($livre);
            $entityManager->flush();

            return $this->redirectToRoute('livre_ajout_succes', ['ajout' => true]);
        }

        return $this->render('livres/ajout.html.twig', [
            'form' => $form->createView(),
            'mode' => 'ajouter',
        ]);
    }

    #[Route('/{_locale}/livres/modifier/{id}', name: 'modifier_livre')]
    public function modifier(Request $request, EntityManagerInterface $entityManager, $id): Response    {
        $livre = $entityManager->getRepository(Livre::class)->find($id);

        if (!$livre) {
            throw $this->createNotFoundException("Le livre avec l'ID $id n'existe pas.");
        }

        $form = $this->createForm(LivreType::class, $livre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('livre_ajout_succes', ['modification' => true]);
        }

        return $this->render('livres/ajout.html.twig', [
            'form' => $form->createView(),
            'mode' => 'modifier',
            'livre' => $livre,
        ]);
    }

    #[Route('/{_locale}/livres/ajout_succes', name: 'livre_ajout_succes')]
    public function ajoutSucces(Request $request): Response
    {
        $livreAjoute = $request->query->get('ajout');
        $livreModifie = $request->query->get('modification');

        $message = $livreAjoute ? "Livre ajouté avec succès ! Merci d'avoir ajouté un livre." : "Livre modifié avec succès ! Merci d'avoir modifié un livre.";

        return $this->render('livres/ajout_succes.html.twig', [
            'message' => $message,
            'retour_url' => $this->generateUrl('livres_index'),
            'livreAjoute' => $livreAjoute, 
            'livreModifie' => $livreModifie, 
        ]);
    }
}
