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
    #[Route('/{_locale}/employes', name: 'employes_index', methods: ['GET'])]
    public function index(Request $request, EmployeRepository $employeRepository): Response
    {
        $allEmployes = $employeRepository->findAll();
        $filterNom = $request->query->get('filterNom', '');
        $sortOrder = $request->query->get('sortOrder', 'ASC');
        $criteria = !empty($filterNom) ? ['nom' => $filterNom] : [];
        $filteredEmployes = $employeRepository->findBy($criteria, ['nom' => $sortOrder]);

        return $this->render('employe/index.html.twig', [
            'employes' => $filteredEmployes,
            'allEmployes' => $allEmployes,
            'filterNom' => $filterNom,
            'sortOrder' => $sortOrder,
        ]);
    }

    #[Route('/{_locale}/employes/detail/{id}', name: 'detail_employe', methods: ['GET'])]
    public function detail(EmployeRepository $employeRepository, int $id): Response
    {
        $employe = $employeRepository->find($id);
        if (!$employe) {
            throw $this->createNotFoundException("L'employé demandé n'existe pas.");
        }
        return $this->render('employe/detail.html.twig', ['employe' => $employe]);
    }

    #[Route('/{_locale}/employes/ajout', name: 'employe_ajout', methods: ['GET', 'POST'])]
    public function ajout(Request $request, EntityManagerInterface $entityManager): Response
    {
        $employe = new Employe();
        $form = $this->createForm(EmployeType::class, $employe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($employe);
            $entityManager->flush();
            return $this->redirectToRoute('employe_ajout_succes', ['employeAjoute' => true]);
        }

        return $this->render('employe/ajout.html.twig', [
            'form' => $form->createView(),
            'mode' => 'ajouter',
        ]);
    }

    #[Route("/{_locale}/employes/modifier/{id}", name: "modifier_employe", methods: ['GET', 'POST'])]
    public function modifier(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $employe = $entityManager->getRepository(Employe::class)->find($id);
        if (!$employe) {
            throw $this->createNotFoundException("L'employé avec l'ID $id n'existe pas.");
        }

        $form = $this->createForm(EmployeType::class, $employe);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('employe_ajout_succes', ['employeModifie' => true]);
        }

        return $this->render('employe/modif.html.twig', [
            'form' => $form->createView(),
            'employe' => $employe,
            'mode' => 'modifier',
        ]);
    }

    #[Route('/{_locale}/employes/ajout_succes', name: 'employe_ajout_succes')]
    public function ajoutSucces(Request $request): Response
    {
        $employeAjoute = $request->query->get('employeAjoute', false);
        $employeModifie = $request->query->get('employeModifie', false);
        $retourUrl = $this->generateUrl('employes_index');

        return $this->render('employe/ajout_succes.html.twig', [
            'retour_url' => $retourUrl,
            'employeAjoute' => $employeAjoute,
            'employeModifie' => $employeModifie
        ]);
    }
}
