<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Form\Type\EmployeType;
use App\Repository\EmployeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmployeController extends AbstractController
{
    #[Route('/employes', name: 'employes_index', methods: ['GET'])]
    public function index(Request $request, EmployeRepository $employeRepository): Response
    {
        // Récupérer tous les employés pour peupler la liste déroulante "Choisir un employé".
        $allEmployes = $employeRepository->findAll();
        
        // Récupérer les paramètres de filtrage et de tri depuis la requête.
        $filterNom = $request->query->get('filterNom', '');
        $sortOrder = $request->query->get('sortOrder', 'ASC');
        
        // Appliquer le filtrage en fonction de l'employé sélectionné, sinon lister tous les employés.
        $criteria = [];
        if ($filterNom !== '') {
            $criteria = ['nom' => $filterNom];
        }

        // Récupérer les employés filtrés et triés.
        $filteredEmployes = $employeRepository->findBy($criteria, ['nom' => $sortOrder]);

        return $this->render('employe/index.html.twig', [
            'employes' => $filteredEmployes,
            'allEmployes' => $allEmployes,
            'filterNom' => $filterNom,
            'sortOrder' => $sortOrder,
        ]);
    }

    #[Route('/employes/detail/{id}', name: 'detail_employe', methods: ['GET'])]
    public function detail(EmployeRepository $employeRepository, int $id): Response
    {
        $employe = $employeRepository->find($id);
        if (!$employe) {
            throw $this->createNotFoundException("L'employé demandé n'existe pas.");
        }
        return $this->render('employe/detail.html.twig', ['employe' => $employe]);
    }

    #[Route('/employes/ajout', name: 'employe_ajout', methods: ['GET', 'POST'])]
    public function ajout(Request $request, EntityManagerInterface $entityManager): Response
    {
        $employe = new Employe();
        $form = $this->createForm(EmployeType::class, $employe);

        // Définir le mode comme "ajouter"
        $mode = 'ajouter';

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($employe);
            $entityManager->flush();
            // Rediriger vers la page de succès d'ajout d'employé
            return $this->redirectToRoute('employe_ajout_succes');
        }
        return $this->render('employe/ajout.html.twig', [
            'form' => $form->createView(),
            'mode' => $mode, // Passer le mode au modèle Twig
        ]);
    }

    #[Route("/employes/modifier/{id}", name: "modifier_employe", methods: ['GET', 'POST'])]
    public function modifier(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $employe = $entityManager->getRepository(Employe::class)->find($id);
        if (!$employe) {
            throw $this->createNotFoundException("L'employé avec l'ID $id n'existe pas.");
        }

        $form = $this->createForm(EmployeType::class, $employe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            // Rediriger vers la page de succès d'ajout d'employé
            return $this->redirectToRoute('employe_ajout_succes');
        }

        return $this->render('employe/modif.html.twig', [
            'form' => $form->createView(),
            'employe' => $employe,
        ]);
    }

    #[Route('/employes/ajout_succes', name: 'employe_ajout_succes')]
    public function ajoutSucces(): Response
    {
        // Définir l'URL de retour à la liste des employés
        $retourUrl = $this->generateUrl('employes_index');
    
        // Définir la variable pour indiquer que l'employé a été ajouté
        $employeAjoute = true;
    
        // Rendre le template en passant les variables nécessaires
        return $this->render('employe/ajout_succes.html.twig', [
            'retour_url' => $retourUrl,
            'employeAjoute' => $employeAjoute
        ]);
    }
}
