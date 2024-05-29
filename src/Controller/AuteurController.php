<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AuteurRepository;
use App\Entity\Auteur;
use App\Form\Type\AuteurType;

class AuteurController extends AbstractController
{
    #[Route('/{_locale}/auteurs', name: 'app_auteur')]
    public function index(Request $request, AuteurRepository $auteurRepository): Response
    {
        $auteurId = $request->query->get('auteurId');
        $sortOrder = $request->query->get('sortOrder');
        
        
        $tousLesAuteurs = $auteurRepository->findAll();
        
        $queryBuilder = $auteurRepository->createQueryBuilder('a');

        if (!empty($auteurId)) {
            $queryBuilder->andWhere('a.id = :auteurId')
                         ->setParameter('auteurId', $auteurId);
        }

        if (!empty($sortOrder) && in_array($sortOrder, ['asc', 'desc'])) {
            $queryBuilder->orderBy('a.nom', $sortOrder);
        } else {
            $queryBuilder->orderBy('a.nom', 'asc');
        }

        $auteurs = $queryBuilder->getQuery()->getResult();

        return $this->render('auteurs/index.html.twig', [
            'tousLesAuteurs' => $tousLesAuteurs, 
            'auteurs' => $auteurs,
            'auteurId' => $auteurId,
            'sortOrder' => $sortOrder,
        ]);
    }

    #[Route('/{_locale}/auteurs/detail/{id}', name: 'detail_auteur', methods: ['GET'])]
    public function detail(EntityManagerInterface $entityManager, int $id): Response
    {
        $auteur = $entityManager->getRepository(Auteur::class)->find($id);

        if (!$auteur) {
            throw $this->createNotFoundException("L'auteur demandé n'existe pas.");
        }

        return $this->render('auteurs/detail.html.twig', [
            'auteur' => $auteur,
        ]);
    }

    #[Route('/{_locale}/auteurs/ajout', name: 'auteur_ajout')]
    public function ajout(Request $request, EntityManagerInterface $entityManager): Response
    {
        $auteur = new Auteur();
        $form = $this->createForm(AuteurType::class, $auteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($auteur);
            $entityManager->flush();

            return $this->redirectToRoute('auteur_ajout_succes', ['ajout' => 'true']);
        }

        return $this->render('auteurs/ajout.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route("/{_locale}/auteurs/modifier/{id}", name: "auteur_modif")]
    public function modifierAuteur(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        $auteur = $entityManager->getRepository(Auteur::class)->find($id);

        if (!$auteur) {
            throw $this->createNotFoundException("L'auteur avec l'ID $id n'existe pas.");
        }

        $form = $this->createForm(AuteurType::class, $auteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('auteur_ajout_succes', ['modification' => 'true']);
        }

        return $this->render('auteurs/modif.html.twig', [
            'form' => $form->createView(),
            'auteur' => $auteur,
        ]);
    }

    #[Route('/{_locale}/auteurs/ajout_succes', name: 'auteur_ajout_succes')]
    public function ajoutSucces(Request $request): Response
    {
        $auteurAjoute = $request->query->get('ajout');
        $auteurModifie = $request->query->get('modification');
        $message = $auteurAjoute ? "auteur ajouté avec succès ! Merci d'avoir ajouté un auteur." : "auteur modifié avec succès ! Merci d'avoir modifié un auteur.";

        return $this->render('auteurs/ajout_succes.html.twig', [
            'auteurAjoute' => $auteurAjoute,
            'auteurModifie' => $auteurModifie,
            'message' => $message,
            'retour_url' => $this->generateUrl('app_auteur'), 
        ]);
    }
}