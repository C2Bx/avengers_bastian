<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\MarquePage; // Assurez-vous que cette entité existe
use Doctrine\ORM\EntityManagerInterface;

// Ajout d'un préfixe aux routes de ce contrôleur
#[Route('/marque-page', name: 'marque_page_')]
class MarquePageController extends AbstractController
{
    // Modification du chemin et du nom pour cette route, pour l'aligner avec le préfixe
    #[Route('/', name: 'afficher')]
    public function afficherTable(EntityManagerInterface $entityManager): Response {
        $marqueP = $entityManager->getRepository(MarquePage::class)->findAll();
        return $this->render('marqueP/tableau.html.twig', [
            'marquesP' => $marqueP,
        ]);
    }

    #[Route("/ajouter", name: "ajouter")]
    public function ajouterMarquePage(EntityManagerInterface $entityManager): Response {
        $marquePage = new MarquePage();
        $marquePage->setUrl("https://exemple.com");
        $marquePage->setCommentaire("Un exemple de commentaire");
        $marquePage->setDateCreation(new \DateTime()); // Ajoute la date et l'heure actuelles

        $entityManager->persist($marquePage);
        $entityManager->flush();

        return new Response("Marque-page ajouté avec succès avec l'id " . $marquePage->getId() . " à la date " . $marquePage->getDateCreation()->format('Y-m-d H:i:s'));
    }

    // Ajout d'une méthode pour afficher les détails d'un marque-page spécifique
    #[Route('/detail/{id}', name: 'detail')]
    public function detail(EntityManagerInterface $entityManager, int $id): Response {
        $marquePage = $entityManager->getRepository(MarquePage::class)->find($id);

        if (!$marquePage) {
            throw $this->createNotFoundException('Le marque-page demandé n\'existe pas');
        }

        return $this->render('marqueP/detail.html.twig', [
            'marquePage' => $marquePage,
        ]);
    }
}
