<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\MarquePage;
use Doctrine\ORM\EntityManagerInterface;

class MarquePageController extends AbstractController
{
    /* public function number(): Response
    {
        $number = random_int(0, 100);

        return new Response(
            '<html><body>Lucky number: '.$number.'</body></html>'
        );
    } */

    #[Route('/marque/page')]
    public function afficherTable(EntityManagerInterface $entityManager): Response {
        $marqueP = $entityManager->getRepository(MarquePage::class)->findAll();
        return $this->render('marqueP/tableau.html.twig', [
            'marquesP' => $marqueP,
        ]);
    }


}

?>