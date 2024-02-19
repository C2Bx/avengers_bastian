<?php

namespace App\Controller;

use App\Entity\MarquePage;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/marque-pages', requirements: ["_locale" => "en|es|fr"], name: "marquepage_")]
class MarquePagesController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $marque_pages = $entityManager->getRepository(MarquePage::class)->findAll();
        return $this->render('marque_pages/index.html.twig', [
            'marque_pages' => $marque_pages
        ]);
    }

    #[Route('/ajouter', name: "ajouter")]
    public function ajoutMarquePage(EntityManagerInterface $entityManager){ // pas bon exemple
        $marque_page = new MarquePage();
        $marque_page->setUrl("https://site.nc/");
        $marque_page->setDateCreation(new \DateTime());
        $marque_page->setCommentaire("Pas de commentaire");
        $entityManager->persist($marque_page);
        $entityManager->flush();
        return new Response("Marque page ajouté avec succès (id :".$marque_page->getId().")");
    }

    #[Route('/details/{id}', name:'details')]
    public function detail(EntityManagerInterface $entityManager, int $id): Response {
        $marquePage = $entityManager->getRepository(MarquePage::class)->find($id);
        if (!$marquePage) {
            throw $this->createNotFoundException("Le marque-page demandé n'existe pas");
        }
        return $this->render('marque_pages/detail.html.twig', [
            'marquePage' => $marquePage,
        ]);
    }
}
