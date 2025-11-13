<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    public function index(): Response
    {
        $panier = [
            ['nom' => 'Pizza Margherita', 'prix' => 12.50, 'quantite' => 2],
            ['nom' => 'Burger Classique', 'prix' => 10.00, 'quantite' => 1],
            ['nom' => 'Tiramisu', 'prix' => 5.50, 'quantite' => 1],
        ];


        $total = 0;
        foreach ($panier as $item) {
            $total += $item['prix'] * $item['quantite'];
        }

        return $this->render('panier/index.html.twig', [
            'panier' => $panier,
            'total' => $total
        ]);
    }
}
