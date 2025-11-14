<?php

namespace App\Controller;

use App\Entity\LignePanier;
use App\Entity\Etudiant;
use App\Entity\Plat;
use App\Repository\LignePanierRepository;
use App\Repository\EtudiantRepository;
use App\Repository\PlatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/lignes-panier', name: 'api_ligne_panier_')]
final class ApiLignePanierController extends AbstractController
{
    public function __construct(
        private LignePanierRepository $lignePanierRepository,
        private EtudiantRepository $etudiantRepository,
        private PlatRepository $platRepository,
        private EntityManagerInterface $entityManager,
        private ValidatorInterface $validator
    ) {
    }

    /**
     * Récupère la liste de toutes les lignes de panier
     */
    #[Route('', name: 'list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $lignesPanier = $this->lignePanierRepository->findAll();

        $data = array_map(function (LignePanier $lignePanier) {
            return [
                'id' => $lignePanier->getId(),
                'etudiant' => [
                    'id' => $lignePanier->getEtudiant()->getId(),
                    'nom' => $lignePanier->getEtudiant()->getNom(),
                    'prenom' => $lignePanier->getEtudiant()->getPrenom(),
                    'email' => $lignePanier->getEtudiant()->getEmail(),
                ],
                'plat' => [
                    'id' => $lignePanier->getPlat()->getId(),
                    'nom' => $lignePanier->getPlat()->getNom(),
                    'prix' => (float) $lignePanier->getPlat()->getPrix(),
                ],
                'quantite' => $lignePanier->getQuantite(),
                'sousTotal' => $lignePanier->getSousTotal(),
                'createdAt' => $lignePanier->getCreatedAt()->format('Y-m-d H:i:s'),
            ];
        }, $lignesPanier);

        return $this->json([
            'success' => true,
            'data' => $data,
            'message' => 'Liste des lignes de panier récupérée avec succès'
        ], Response::HTTP_OK);
    }

    /**
     * Récupère les détails d'une ligne de panier
     */
    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $lignePanier = $this->lignePanierRepository->find($id);

        if (!$lignePanier) {
            return $this->json([
                'success' => false,
                'message' => 'Ligne de panier non trouvée'
            ], Response::HTTP_NOT_FOUND);
        }

        $data = [
            'id' => $lignePanier->getId(),
            'etudiant' => [
                'id' => $lignePanier->getEtudiant()->getId(),
                'nom' => $lignePanier->getEtudiant()->getNom(),
                'prenom' => $lignePanier->getEtudiant()->getPrenom(),
                'email' => $lignePanier->getEtudiant()->getEmail(),
            ],
            'plat' => [
                'id' => $lignePanier->getPlat()->getId(),
                'nom' => $lignePanier->getPlat()->getNom(),
                'prix' => (float) $lignePanier->getPlat()->getPrix(),
            ],
            'quantite' => $lignePanier->getQuantite(),
            'sousTotal' => $lignePanier->getSousTotal(),
            'createdAt' => $lignePanier->getCreatedAt()->format('Y-m-d H:i:s'),
        ];

        return $this->json([
            'success' => true,
            'data' => $data,
            'message' => 'Ligne de panier récupérée avec succès'
        ], Response::HTTP_OK);
    }

    /**
     * Crée une nouvelle ligne de panier
     */
    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return $this->json([
                'success' => false,
                'message' => 'Données JSON invalides'
            ], Response::HTTP_BAD_REQUEST);
        }

        $etudiant = $this->etudiantRepository->find($data['etudiant_id'] ?? 0);
        if (!$etudiant) {
            return $this->json([
                'success' => false,
                'message' => 'Étudiant non trouvé'
            ], Response::HTTP_NOT_FOUND);
        }

        $plat = $this->platRepository->find($data['plat_id'] ?? 0);
        if (!$plat) {
            return $this->json([
                'success' => false,
                'message' => 'Plat non trouvé'
            ], Response::HTTP_NOT_FOUND);
        }

        $lignePanier = new LignePanier();
        $lignePanier->setEtudiant($etudiant);
        $lignePanier->setPlat($plat);
        $lignePanier->setQuantite($data['quantite'] ?? 1);

        $errors = $this->validator->validate($lignePanier);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getPropertyPath() . ': ' . $error->getMessage();
            }
            return $this->json([
                'success' => false,
                'message' => 'Erreurs de validation',
                'errors' => $errorMessages
            ], Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->persist($lignePanier);
        $this->entityManager->flush();

        return $this->json([
            'success' => true,
            'data' => [
                'id' => $lignePanier->getId(),
                'etudiant' => [
                    'id' => $lignePanier->getEtudiant()->getId(),
                    'nom' => $lignePanier->getEtudiant()->getNom(),
                ],
                'plat' => [
                    'id' => $lignePanier->getPlat()->getId(),
                    'nom' => $lignePanier->getPlat()->getNom(),
                ],
                'quantite' => $lignePanier->getQuantite(),
                'sousTotal' => $lignePanier->getSousTotal(),
            ],
            'message' => 'Ligne de panier créée avec succès'
        ], Response::HTTP_CREATED);
    }

    /**
     * Met à jour une ligne de panier existante
     */
    #[Route('/{id}', name: 'update', methods: ['PUT', 'PATCH'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $lignePanier = $this->lignePanierRepository->find($id);

        if (!$lignePanier) {
            return $this->json([
                'success' => false,
                'message' => 'Ligne de panier non trouvée'
            ], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return $this->json([
                'success' => false,
                'message' => 'Données JSON invalides'
            ], Response::HTTP_BAD_REQUEST);
        }

        if (isset($data['etudiant_id'])) {
            $etudiant = $this->etudiantRepository->find($data['etudiant_id']);
            if (!$etudiant) {
                return $this->json([
                    'success' => false,
                    'message' => 'Étudiant non trouvé'
                ], Response::HTTP_NOT_FOUND);
            }
            $lignePanier->setEtudiant($etudiant);
        }

        if (isset($data['plat_id'])) {
            $plat = $this->platRepository->find($data['plat_id']);
            if (!$plat) {
                return $this->json([
                    'success' => false,
                    'message' => 'Plat non trouvé'
                ], Response::HTTP_NOT_FOUND);
            }
            $lignePanier->setPlat($plat);
        }

        if (isset($data['quantite'])) {
            $lignePanier->setQuantite($data['quantite']);
        }

        $errors = $this->validator->validate($lignePanier);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getPropertyPath() . ': ' . $error->getMessage();
            }
            return $this->json([
                'success' => false,
                'message' => 'Erreurs de validation',
                'errors' => $errorMessages
            ], Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->flush();

        return $this->json([
            'success' => true,
            'data' => [
                'id' => $lignePanier->getId(),
                'etudiant' => [
                    'id' => $lignePanier->getEtudiant()->getId(),
                    'nom' => $lignePanier->getEtudiant()->getNom(),
                ],
                'plat' => [
                    'id' => $lignePanier->getPlat()->getId(),
                    'nom' => $lignePanier->getPlat()->getNom(),
                ],
                'quantite' => $lignePanier->getQuantite(),
                'sousTotal' => $lignePanier->getSousTotal(),
            ],
            'message' => 'Ligne de panier mise à jour avec succès'
        ], Response::HTTP_OK);
    }

    /**
     * Supprime une ligne de panier
     */
    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $lignePanier = $this->lignePanierRepository->find($id);

        if (!$lignePanier) {
            return $this->json([
                'success' => false,
                'message' => 'Ligne de panier non trouvée'
            ], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($lignePanier);
        $this->entityManager->flush();

        return $this->json([
            'success' => true,
            'message' => 'Ligne de panier supprimée avec succès'
        ], Response::HTTP_OK);
    }

    /**
     * Récupère toutes les lignes de panier d'un étudiant
     */
    #[Route('/etudiant/{etudiantId}', name: 'by_etudiant', methods: ['GET'])]
    public function byEtudiant(int $etudiantId): JsonResponse
    {
        $etudiant = $this->etudiantRepository->find($etudiantId);

        if (!$etudiant) {
            return $this->json([
                'success' => false,
                'message' => 'Étudiant non trouvé'
            ], Response::HTTP_NOT_FOUND);
        }

        $lignesPanier = $this->lignePanierRepository->findBy(['etudiant' => $etudiant]);

        $data = array_map(function (LignePanier $lignePanier) {
            return [
                'id' => $lignePanier->getId(),
                'plat' => [
                    'id' => $lignePanier->getPlat()->getId(),
                    'nom' => $lignePanier->getPlat()->getNom(),
                    'prix' => (float) $lignePanier->getPlat()->getPrix(),
                ],
                'quantite' => $lignePanier->getQuantite(),
                'sousTotal' => $lignePanier->getSousTotal(),
                'createdAt' => $lignePanier->getCreatedAt()->format('Y-m-d H:i:s'),
            ];
        }, $lignesPanier);

        $total = array_sum(array_column($data, 'sousTotal'));

        return $this->json([
            'success' => true,
            'data' => $data,
            'total' => $total,
            'message' => 'Panier de l\'étudiant récupéré avec succès'
        ], Response::HTTP_OK);
    }
}

