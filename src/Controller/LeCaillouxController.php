<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Faune;
use App\Entity\Flore;
use Symfony\Component\Routing\Annotation\Route;

class LeCaillouxController extends AbstractController
{
    #[Route('/{_locale}/faune', name: 'Faune', methods: ['GET'], requirements: ['_locale' => 'fr|en|es'])]
    public function afficherTableFaune(EntityManagerInterface $entityManager): Response {
        $faune = $entityManager->getRepository(Faune::class)->findAll();
        return $this->render('lecailloux/faune.html.twig', [
            'faune' => $faune,
        ]);
    }

    #[Route('/{_locale}/flore', name: 'Flore', methods: ['GET'], requirements: ['_locale' => 'fr|en|es'])]
    public function afficherTableFlore(EntityManagerInterface $entityManager): Response {
        $flore = $entityManager->getRepository(Flore::class)->findAll();
        return $this->render('lecailloux/flore.html.twig', [
            'flore' => $flore,
        ]);
    }
}
