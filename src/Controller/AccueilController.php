<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class AccueilController extends AbstractController
{
    #[Route(
        path: '/',
        name: 'app_accueil_root'
    )]
    public function redirectToLocale(Request $request): Response
    {
        return $this->redirectToRoute('app_accueil', ['_locale' => 'fr']);
    }

    #[Route(
        path: '/{_locale}/',
        name: 'app_accueil',
        requirements: [
            '_locale' => 'es|en|fr'
        ]
    )]
    public function index(Request $request): Response
    {
        $locale = $request->getLocale();
        return $this->render('accueil/index.html.twig', [
            'controller_name' => 'AccueilController',
            '_locale' => $locale,
        ]);
    }
}
