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
    #[Route('/{_locale}', name: 'marquepage_index', methods: ['GET'])]
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

    #[Route('/{_locale}/detail/{id}', name: 'marquepage_detail', methods: ['GET'])]
    public function detail(MarquePageRepository $marquePageRepository, int $id): Response
    {
        $marquePage = $marquePageRepository->find($id);
        if (!$marquePage) {
            throw $this->createNotFoundException($this->trans("Le marque-page demandé n'existe pas.", [], 'messages'));
        }
        return $this->render('marque_pages/detail.html.twig', ['marquePage' => $marquePage]);
    }

    #[Route('/{_locale}/ajouter', name: 'marquepage_ajout', methods: ['GET', 'POST'])]
    public function ajout(Request $request, EntityManagerInterface $entityManager, MotsClesRepository $motsClesRepository): Response
    {
        $marquePage = new MarquePage();
        $form = $this->createForm(MarquePageType::class, $marquePage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newMotsCles = $request->request->get('new_motsCles');

            $newMotsClesArray = explode(',', $newMotsCles);

            foreach ($newMotsClesArray as $newMotCle) {
                $existingMotCle = $motsClesRepository->findOneBy(['motCle' => trim($newMotCle)]);

                if (!$existingMotCle) {
                    $newMotsCle = new MotsCles();
                    $newMotsCle->setMotCle(trim($newMotCle));
                    $entityManager->persist($newMotsCle);
                    $marquePage->addMotsCle($newMotsCle);
                } else {
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

    #[Route('/{_locale}/modifier/{id}', name: 'marquepage_modifier', methods: ['GET', 'POST'])]
    public function modifier(Request $request, MarquePageRepository $marquePageRepository, EntityManagerInterface $entityManager, int $id): Response
    {
        $marquePage = $marquePageRepository->find($id);
        if (!$marquePage) {
            throw $this->createNotFoundException($this->trans("Le marque-page avec l'ID {id} n'existe pas.", ['id' => $id], 'messages'));
        }
        $form = $this->createForm(MarquePageType::class, $marquePage);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $newMotCle = $request->request->get('new_motCle');

            if (!empty($newMotCle)) {
                $motCle = new MotsCles();
                $motCle->setMotCle($newMotCle);

                $marquePage->addMotsCle($motCle);

                $entityManager->persist($motCle);
            }

            $entityManager->flush();

            return $this->redirectToRoute('marquepage_ajout_succes', ['marquePageModifie' => true]);
        }
        return $this->render('marque_pages/modif.html.twig', [
            'form' => $form->createView(),
            'marquePage' => $marquePage,
        ]);
    }

    #[Route('/{_locale}/ajout_succes', name: 'marquepage_ajout_succes')]
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

    #[Route('/{_locale}/details/{id}', name: 'marquepage_details', methods: ['GET'])]
    public function details(MarquePageRepository $marquePageRepository, int $id): Response
    {
        $marquePage = $marquePageRepository->find($id);
        if (!$marquePage) {
            throw $this->createNotFoundException($this->trans("Le marque-page demandé n'existe pas.", [], 'messages'));
        }
        return $this->render('marque_pages/detail.html.twig', ['marquePage' => $marquePage]);
    }
}
