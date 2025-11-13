<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'Koman',
        ]);
    }

    #[Route('/etudiant', name: 'app_etudiant')]
    public function etudiant(): Response
    {
        return $this->render('etudiant/index.html.twig', [
            'nom' => 'Doe',
            'prenom' => 'Jane',
            'email' => 'jane.doe@example.com',
            'filiere' => 'Informatique',
            'niveau' => 'Master 1',
            'numeroCarte' => 'CROUS123456',
        ]);
    }
}
