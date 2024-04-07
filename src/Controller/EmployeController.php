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
    public function index(EmployeRepository $employeRepository, Request $request): Response
    {
        $filterNom = $request->query->get('filterNom'); // Retrieve filterNom from request
        $sortOrder = $request->query->get('sortOrder'); // Retrieve sortOrder from request

        // Fetch employes data from repository
        $employes = $employeRepository->findAll();

        // Pass employes, filterNom, and sortOrder to the template
        return $this->render('employe/index.html.twig', [
            'employes' => $employes,
            'filterNom' => $filterNom,
            'sortOrder' => $sortOrder, // Pass sortOrder to the template
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
        $form->handleRequest($request);

        $mode = 'ajouter'; // Définir le mode comme "ajouter"

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($employe);
            $entityManager->flush();
            return $this->redirectToRoute('employes_success', ['action' => 'ajoute']);
        }

        return $this->render('employe/ajout.html.twig', [
            'form' => $form->createView(),
            'mode' => $mode, // Passer la variable mode au template
        ]);
    }

    #[Route("/employes/modifier/{id}", name: "modifier_employe", methods: ['GET', 'POST'])]
    public function modifier(Request $request, EmployeRepository $employeRepository, EntityManagerInterface $entityManager, int $id): Response
    {
        $employe = $employeRepository->find($id);
        if (!$employe) {
            throw $this->createNotFoundException("L'employé avec l'ID $id n'existe pas.");
        }

        $form = $this->createForm(EmployeType::class, $employe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('employes_success', ['action' => 'modifie']);
        }

        return $this->render('employe/modif.html.twig', [
            'form' => $form->createView(),
            'employe' => $employe,
        ]);
    }

    #[Route('/employes/success', name: 'employes_success')]
    public function success(Request $request): Response
    {
        $action = $request->query->get('action', '');

        $employeAjoute = $action === 'ajoute';
        $employeModifie = $action === 'modifie';

        return $this->render('employe/ajout_succes.html.twig', [
            'employeAjoute' => $employeAjoute,
            'employeModifie' => $employeModifie,
            'retour_url' => $this->generateUrl('employes_index'),
        ]);
    }
}
