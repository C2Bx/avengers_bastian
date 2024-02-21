<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Repository\LivreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/livres')]
class LivreController extends AbstractController
{
    #[Route('/', name: 'livre_liste')]
    public function index(LivreRepository $livreRepository): Response
    {
        $livres = $livreRepository->findAll();
        return $this->render('livres/index.html.twig', ['livres' => $livres]);
    }

    #[Route('/{id}', name: 'livre_detail', requirements: ['id' => '\d+'])]
    public function detail(Livre $livre): Response
    {
        return $this->render('livres/detail.html.twig', ['livre' => $livre]);
    }
}
