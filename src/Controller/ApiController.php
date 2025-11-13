<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api', name: 'api_')]
final class ApiController extends AbstractController
{
    /**
     * Récupère la liste des plats disponibles
     */
    #[Route('/menu', name: 'menu', methods: ['GET'])]
    public function getMenu(): JsonResponse
    {
        $menu = [
            [
                'id' => 1,
                'nom' => 'Pizza Margherita',
                'description' => 'Tomate, mozzarella, basilic',
                'prix' => 8.90,
                'categorie' => 'Pizza',
                'image' => 'https://www.eurialfoodservice-industry.fr/wp-content/uploads/2022/12/PEPPERONI-1024x823.jpg'
            ],
            [
                'id' => 2,
                'nom' => 'Burger Deluxe',
                'description' => 'Steak, cheddar, salade, tomate',
                'prix' => 9.50,
                'categorie' => 'Burger',
                'image' => 'https://www.primaverakitchen.com/wp-content/uploads/2024/08/Hamburger-Recipe-7.jpg'
            ],
            [
                'id' => 3,
                'nom' => 'Ramen Tonkotsu',
                'description' => 'Nouilles, porc, œuf, algues',
                'prix' => 10.90,
                'categorie' => 'Asiatique',
                'image' => 'https://www.healthyfoodcreation.fr/wp-content/uploads/2023/01/ramen-2.jpg'
            ],
            [
                'id' => 4,
                'nom' => 'Salade César',
                'description' => 'Poulet grillé, parmesan, croûtons',
                'prix' => 7.50,
                'categorie' => 'Salade',
                'image' => 'https://rians.com/wp-content/uploads/2024/04/1000038128.jpg'
            ],
            [
                'id' => 5,
                'nom' => 'Assortiment Sushi',
                'description' => '12 pièces variées',
                'prix' => 12.90,
                'categorie' => 'Asiatique',
                'image' => 'https://offloadmedia.feverup.com/lillesecret.com/wp-content/uploads/2023/03/12102658/COUV-ARTICLES-1920x1080-2023-03-09T161912.579-1024x576.jpg'
            ],
            [
                'id' => 6,
                'nom' => 'Tacos XXL',
                'description' => 'Viande, frites, sauce au choix',
                'prix' => 8.50,
                'categorie' => 'Fast-Food',
                'image' => 'https://www.leregalvire.com/img_s1/151758/boutique/tacos_5_viandes.jpg'
            ],
        ];

        return $this->json([
            'success' => true,
            'data' => $menu,
            'count' => count($menu)
        ], Response::HTTP_OK);
    }

    /**
     * Récupère un plat par son ID
     */
    #[Route('/menu/{id}', name: 'menu_item', methods: ['GET'])]
    public function getMenuItem(int $id): JsonResponse
    {
        $menu = [
            1 => [
                'id' => 1,
                'nom' => 'Pizza Margherita',
                'description' => 'Tomate, mozzarella, basilic',
                'prix' => 8.90,
                'categorie' => 'Pizza',
                'image' => 'https://www.eurialfoodservice-industry.fr/wp-content/uploads/2022/12/PEPPERONI-1024x823.jpg'
            ],
            2 => [
                'id' => 2,
                'nom' => 'Burger Deluxe',
                'description' => 'Steak, cheddar, salade, tomate',
                'prix' => 9.50,
                'categorie' => 'Burger',
                'image' => 'https://www.primaverakitchen.com/wp-content/uploads/2024/08/Hamburger-Recipe-7.jpg'
            ],
            3 => [
                'id' => 3,
                'nom' => 'Ramen Tonkotsu',
                'description' => 'Nouilles, porc, œuf, algues',
                'prix' => 10.90,
                'categorie' => 'Asiatique',
                'image' => 'https://www.healthyfoodcreation.fr/wp-content/uploads/2023/01/ramen-2.jpg'
            ],
            4 => [
                'id' => 4,
                'nom' => 'Salade César',
                'description' => 'Poulet grillé, parmesan, croûtons',
                'prix' => 7.50,
                'categorie' => 'Salade',
                'image' => 'https://rians.com/wp-content/uploads/2024/04/1000038128.jpg'
            ],
            5 => [
                'id' => 5,
                'nom' => 'Assortiment Sushi',
                'description' => '12 pièces variées',
                'prix' => 12.90,
                'categorie' => 'Asiatique',
                'image' => 'https://offloadmedia.feverup.com/lillesecret.com/wp-content/uploads/2023/03/12102658/COUV-ARTICLES-1920x1080-2023-03-09T161912.579-1024x576.jpg'
            ],
            6 => [
                'id' => 6,
                'nom' => 'Tacos XXL',
                'description' => 'Viande, frites, sauce au choix',
                'prix' => 8.50,
                'categorie' => 'Fast-Food',
                'image' => 'https://www.leregalvire.com/img_s1/151758/boutique/tacos_5_viandes.jpg'
            ],
        ];

        if (!isset($menu[$id])) {
            return $this->json([
                'success' => false,
                'message' => 'Plat non trouvé'
            ], Response::HTTP_NOT_FOUND);
        }

        return $this->json([
            'success' => true,
            'data' => $menu[$id]
        ], Response::HTTP_OK);
    }

    /**
     * Récupère le contenu du panier
     */
    #[Route('/panier', name: 'panier', methods: ['GET'])]
    public function getPanier(): JsonResponse
    {
        // Exemple de données simulées (produits ajoutés au panier)
        $panier = [
            [
                'id' => 1,
                'nom' => 'Pizza Margherita',
                'prix' => 12.50,
                'quantite' => 2,
                'sous_total' => 25.00
            ],
            [
                'id' => 2,
                'nom' => 'Burger Classique',
                'prix' => 10.00,
                'quantite' => 1,
                'sous_total' => 10.00
            ],
            [
                'id' => 5,
                'nom' => 'Tiramisu',
                'prix' => 5.50,
                'quantite' => 1,
                'sous_total' => 5.50
            ],
        ];

        // Calcul du total
        $total = 0;
        foreach ($panier as $item) {
            $total += $item['sous_total'];
        }

        return $this->json([
            'success' => true,
            'data' => [
                'items' => $panier,
                'total' => $total,
                'count' => count($panier)
            ]
        ], Response::HTTP_OK);
    }

    /**
     * Ajoute un produit au panier
     */
    #[Route('/panier', name: 'panier_add', methods: ['POST'])]
    public function addToPanier(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['produit_id']) || !isset($data['quantite'])) {
            return $this->json([
                'success' => false,
                'message' => 'Les champs produit_id et quantite sont requis'
            ], Response::HTTP_BAD_REQUEST);
        }

        $produitId = $data['produit_id'];
        $quantite = (int) $data['quantite'];

        if ($quantite <= 0) {
            return $this->json([
                'success' => false,
                'message' => 'La quantité doit être supérieure à 0'
            ], Response::HTTP_BAD_REQUEST);
        }

        // Simulation : récupération du produit
        $menu = [
            1 => ['id' => 1, 'nom' => 'Pizza Margherita', 'prix' => 8.90],
            2 => ['id' => 2, 'nom' => 'Burger Deluxe', 'prix' => 9.50],
            3 => ['id' => 3, 'nom' => 'Ramen Tonkotsu', 'prix' => 10.90],
        ];

        if (!isset($menu[$produitId])) {
            return $this->json([
                'success' => false,
                'message' => 'Produit non trouvé'
            ], Response::HTTP_NOT_FOUND);
        }

        $produit = $menu[$produitId];
        $sousTotal = $produit['prix'] * $quantite;

        return $this->json([
            'success' => true,
            'message' => 'Produit ajouté au panier',
            'data' => [
                'id' => $produitId,
                'nom' => $produit['nom'],
                'prix' => $produit['prix'],
                'quantite' => $quantite,
                'sous_total' => $sousTotal
            ]
        ], Response::HTTP_CREATED);
    }

    /**
     * Supprime un produit du panier
     */
    #[Route('/panier/{id}', name: 'panier_remove', methods: ['DELETE'])]
    public function removeFromPanier(int $id): JsonResponse
    {
        // Simulation : vérification si le produit existe dans le panier
        $panierItems = [1, 2, 5]; // IDs des produits dans le panier

        if (!in_array($id, $panierItems)) {
            return $this->json([
                'success' => false,
                'message' => 'Produit non trouvé dans le panier'
            ], Response::HTTP_NOT_FOUND);
        }

        return $this->json([
            'success' => true,
            'message' => 'Produit supprimé du panier'
        ], Response::HTTP_OK);
    }

    /**
     * Vide complètement le panier
     */
    #[Route('/panier', name: 'panier_clear', methods: ['DELETE'])]
    public function clearPanier(): JsonResponse
    {
        return $this->json([
            'success' => true,
            'message' => 'Panier vidé avec succès'
        ], Response::HTTP_OK);
    }

    /**
     * Récupère les informations d'un étudiant
     */
    #[Route('/etudiant', name: 'etudiant', methods: ['GET'])]
    public function getEtudiant(): JsonResponse
    {
        $etudiant = [
            'id' => 1,
            'nom' => 'Doe',
            'prenom' => 'Jane',
            'email' => 'jane.doe@example.com',
            'filiere' => 'Informatique',
            'niveau' => 'Master 1',
            'numeroCarte' => 'CROUS123456',
            'solde' => 45.50
        ];

        return $this->json([
            'success' => true,
            'data' => $etudiant
        ], Response::HTTP_OK);
    }

    /**
     * Récupère les catégories de plats disponibles
     */
    #[Route('/categories', name: 'categories', methods: ['GET'])]
    public function getCategories(): JsonResponse
    {
        $categories = [
            ['id' => 1, 'nom' => 'Pizza', 'description' => 'Nos délicieuses pizzas'],
            ['id' => 2, 'nom' => 'Burger', 'description' => 'Burgers gourmands'],
            ['id' => 3, 'nom' => 'Asiatique', 'description' => 'Plats asiatiques authentiques'],
            ['id' => 4, 'nom' => 'Salade', 'description' => 'Salades fraîches et équilibrées'],
            ['id' => 5, 'nom' => 'Fast-Food', 'description' => 'Fast-food de qualité'],
        ];

        return $this->json([
            'success' => true,
            'data' => $categories,
            'count' => count($categories)
        ], Response::HTTP_OK);
    }

    /**
     * Récupère les plats d'une catégorie
     */
    #[Route('/menu/categorie/{categorie}', name: 'menu_by_category', methods: ['GET'])]
    public function getMenuByCategory(string $categorie): JsonResponse
    {
        $menu = [
            'Pizza' => [
                [
                    'id' => 1,
                    'nom' => 'Pizza Margherita',
                    'description' => 'Tomate, mozzarella, basilic',
                    'prix' => 8.90,
                    'categorie' => 'Pizza'
                ]
            ],
            'Burger' => [
                [
                    'id' => 2,
                    'nom' => 'Burger Deluxe',
                    'description' => 'Steak, cheddar, salade, tomate',
                    'prix' => 9.50,
                    'categorie' => 'Burger'
                ]
            ],
            'Asiatique' => [
                [
                    'id' => 3,
                    'nom' => 'Ramen Tonkotsu',
                    'description' => 'Nouilles, porc, œuf, algues',
                    'prix' => 10.90,
                    'categorie' => 'Asiatique'
                ],
                [
                    'id' => 5,
                    'nom' => 'Assortiment Sushi',
                    'description' => '12 pièces variées',
                    'prix' => 12.90,
                    'categorie' => 'Asiatique'
                ]
            ],
        ];

        $categorieNormalisee = ucfirst($categorie);

        if (!isset($menu[$categorieNormalisee])) {
            return $this->json([
                'success' => false,
                'message' => 'Catégorie non trouvée'
            ], Response::HTTP_NOT_FOUND);
        }

        return $this->json([
            'success' => true,
            'data' => $menu[$categorieNormalisee],
            'count' => count($menu[$categorieNormalisee])
        ], Response::HTTP_OK);
    }
}

