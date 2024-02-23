<?php

namespace App\Controller;

use App\Entity\MarquePage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/marque-pages', requirements: ["_locale" => "en|es|fr"], name: "marquepage_")]
class MarquePagesController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Récupère tous les marque-pages
        $marque_pages = $entityManager->getRepository(MarquePage::class)->findAll();
        // Rend la vue 'marque_pages/index.html.twig' en passant les marque-pages récupérés
        return $this->render('marque_pages/index.html.twig', [
            'marque_pages' => $marque_pages
        ]);
    }

    #[Route('/details/{id}', name:'details')]
    public function detail(EntityManagerInterface $entityManager, int $id): Response {
        // Récupère le marque-page correspondant à l'ID passé en paramètre
        $marquePage = $entityManager->getRepository(MarquePage::class)->find($id);
        // Si le marque-page n'existe pas, lance une exception 404
        if (!$marquePage) {
            throw $this->createNotFoundException("Le marque-page demandé n'existe pas");
        }
        // Rend la vue 'marque_pages/detail.html.twig' en passant le marque-page récupéré
        return $this->render('marque_pages/detail.html.twig', [
            'marquePage' => $marquePage,
        ]);
    }
}
