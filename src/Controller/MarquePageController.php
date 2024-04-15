<?php

namespace App\Controller;

use App\Entity\MarquePage;
use App\Entity\MotsCles;
use App\Form\Type\MarquePageType;
use App\Repository\MarquePageRepository;
use App\Repository\MotsClesRepository;
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
    public function ajout(Request $request, EntityManagerInterface $entityManager, MotsClesRepository $motsClesRepository): Response
    {
        $marquePage = new MarquePage();
        $form = $this->createForm(MarquePageType::class, $marquePage);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer les nouveaux mots-clés saisis dans le champ de formulaire
            $newMotsCles = $request->request->get('new_motsCles');

            // Séparer les nouveaux mots-clés saisis par des virgules
            $newMotsClesArray = explode(',', $newMotsCles);

            // Parcourir les nouveaux mots-clés
            foreach ($newMotsClesArray as $newMotCle) {
                // Vérifier si le mot-clé existe déjà dans la base de données
                $existingMotCle = $motsClesRepository->findOneBy(['motCle' => trim($newMotCle)]);

                // S'il n'existe pas, le créer et l'associer au marque-page
                if (!$existingMotCle) {
                    $newMotsCle = new MotsCles();
                    $newMotsCle->setMotCle(trim($newMotCle));
                    $entityManager->persist($newMotsCle);
                    $marquePage->addMotsCle($newMotsCle);
                } else {
                    // S'il existe, l'associer directement au marque-page
                    $marquePage->addMotsCle($existingMotCle);
                }
            }

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
            // Récupérer le nouveau mot-clé saisi dans le champ new_motCle du formulaire
            $newMotCle = $request->request->get('new_motCle');

            // Si un nouveau mot-clé est saisi
            if (!empty($newMotCle)) {
                // Créer un nouvel objet MotsCles
                $motCle = new MotsCles();
                $motCle->setMotCle($newMotCle);

                // Associer le nouveau mot-clé au marque-page
                $marquePage->addMotsCle($motCle);

                // Persistez le nouvel objet MotsCles
                $entityManager->persist($motCle);
            }

            // Enregistrer les modifications du marque-page
            $entityManager->flush();

            // Rediriger vers une page de succès ou une autre page
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
