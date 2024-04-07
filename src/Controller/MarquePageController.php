<?php

namespace App\Controller;

use App\Entity\MarquePage;
use App\Form\Type\MarquePageType;
use App\Repository\MarquePageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/marque-pages')]
class MarquePageController extends AbstractController
{
    #[Route('/', name: 'marquepage_index', methods: ['GET'])]
    public function index(Request $request, MarquePageRepository $marquePageRepository): Response
    {
        $allMarquePages = $marquePageRepository->findAll();
        $filterUrl = $request->query->get('filterUrl', '');
        $sortOrder = $request->query->get('sortOrder', 'ASC');
        $criteria = $filterUrl !== '' ? ['url' => $filterUrl] : [];
        $filteredMarquePages = $marquePageRepository->findBy($criteria, ['url' => $sortOrder]);
        
        return $this->render('marque_pages/index.html.twig', [
            'marque_pages' => $filteredMarquePages,
            'allMarquePages' => $allMarquePages,
            'filterUrl' => $filterUrl,
            'sortOrder' => $sortOrder,
        ]);
    }

    #[Route('/detail/{id}', name: 'marquepage_detail', methods: ['GET'])]
    public function detail(MarquePageRepository $marquePageRepository, int $id): Response
    {
        $marquePage = $marquePageRepository->find($id);
        if (!$marquePage) {
            throw $this->createNotFoundException("Le marque-page demandé n'existe pas.");
        }
        return $this->render('marque_pages/detail.html.twig', ['marquePage' => $marquePage]);
    }

    #[Route('/ajouter', name: 'marquepage_ajout', methods: ['GET', 'POST'])]
    public function ajout(Request $request, EntityManagerInterface $entityManager): Response
    {
        $marquePage = new MarquePage();
        $form = $this->createForm(MarquePageType::class, $marquePage);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($marquePage);
            $entityManager->flush();
            return $this->redirectToRoute('marquepage_ajout_succes');
        }
        return $this->render('marque_pages/ajout.html.twig', [
            'form' => $form->createView(),
            'mode' => 'ajouter',
        ]);
    }

    #[Route("/modifier/{id}", name: "marquepage_modifier", methods: ['GET', 'POST'])]
    public function modifier(Request $request, MarquePageRepository $marquePageRepository, EntityManagerInterface $entityManager, int $id): Response
    {
        $marquePage = $marquePageRepository->find($id);
        if (!$marquePage) {
            throw $this->createNotFoundException("Le marque-page avec l'ID $id n'existe pas.");
        }
        $form = $this->createForm(MarquePageType::class, $marquePage);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('marquepage_ajout_succes', ['marquePageModifie' => true]);
        }
        return $this->render('marque_pages/modif.html.twig', [
            'form' => $form->createView(),
            'marquePage' => $marquePage,
        ]);
    }

    #[Route('/ajout_succes', name: 'marquepage_ajout_succes')]
    public function ajoutSucces(Request $request): Response
    {
        $marquePageModifie = $request->query->get('marquePageModifie', false);
        $marquePageAjoute = !$marquePageModifie;

        return $this->render('marque_pages/ajout_succes.html.twig', [
            'retour_url' => $this->generateUrl('marquepage_index'),
            'marquePageAjoute' => $marquePageAjoute,
            'marquePageModifie' => $marquePageModifie,
        ]);
    }

    #[Route('/details/{id}', name: 'marquepage_details', methods: ['GET'])]
    public function details(MarquePageRepository $marquePageRepository, int $id): Response
    {
        $marquePage = $marquePageRepository->find($id);
        if (!$marquePage) {
            throw $this->createNotFoundException("Le marque-page demandé n'existe pas.");
        }
        return $this->render('marque_pages/detail.html.twig', ['marquePage' => $marquePage]);
    }
}
