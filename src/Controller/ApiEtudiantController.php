<?php

namespace App\Controller;

use App\Entity\Etudiant;
use App\Repository\EtudiantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/etudiants', name: 'api_etudiant_')]
final class ApiEtudiantController extends AbstractController
{
    public function __construct(
        private EtudiantRepository $etudiantRepository,
        private EntityManagerInterface $entityManager,
        private ValidatorInterface $validator
    ) {
    }

    /**
     * Récupère la liste de tous les étudiants
     */
    #[Route('', name: 'list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $etudiants = $this->etudiantRepository->findAll();

        $data = array_map(function (Etudiant $etudiant) {
            return [
                'id' => $etudiant->getId(),
                'nom' => $etudiant->getNom(),
                'prenom' => $etudiant->getPrenom(),
                'email' => $etudiant->getEmail(),
                'filiere' => $etudiant->getFiliere(),
                'niveau' => $etudiant->getNiveau(),
                'numeroCarte' => $etudiant->getNumeroCarte(),
                'solde' => $etudiant->getSolde() ? (float) $etudiant->getSolde() : 0.0,
            ];
        }, $etudiants);

        return $this->json([
            'success' => true,
            'data' => $data,
            'message' => 'Liste des étudiants récupérée avec succès'
        ], Response::HTTP_OK);
    }

    /**
     * Récupère les détails d'un étudiant
     */
    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $etudiant = $this->etudiantRepository->find($id);

        if (!$etudiant) {
            return $this->json([
                'success' => false,
                'message' => 'Étudiant non trouvé'
            ], Response::HTTP_NOT_FOUND);
        }

        $data = [
            'id' => $etudiant->getId(),
            'nom' => $etudiant->getNom(),
            'prenom' => $etudiant->getPrenom(),
            'email' => $etudiant->getEmail(),
            'filiere' => $etudiant->getFiliere(),
            'niveau' => $etudiant->getNiveau(),
            'numeroCarte' => $etudiant->getNumeroCarte(),
            'solde' => $etudiant->getSolde() ? (float) $etudiant->getSolde() : 0.0,
        ];

        return $this->json([
            'success' => true,
            'data' => $data,
            'message' => 'Étudiant récupéré avec succès'
        ], Response::HTTP_OK);
    }

    /**
     * Crée un nouvel étudiant
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

        $etudiant = new Etudiant();
        $etudiant->setNom($data['nom'] ?? '');
        $etudiant->setPrenom($data['prenom'] ?? '');
        $etudiant->setEmail($data['email'] ?? '');
        $etudiant->setFiliere($data['filiere'] ?? '');
        $etudiant->setNiveau($data['niveau'] ?? '');
        $etudiant->setNumeroCarte($data['numeroCarte'] ?? '');
        $etudiant->setSolde($data['solde'] ?? null);

        $errors = $this->validator->validate($etudiant);
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

        $this->entityManager->persist($etudiant);
        $this->entityManager->flush();

        return $this->json([
            'success' => true,
            'data' => [
                'id' => $etudiant->getId(),
                'nom' => $etudiant->getNom(),
                'prenom' => $etudiant->getPrenom(),
                'email' => $etudiant->getEmail(),
                'filiere' => $etudiant->getFiliere(),
                'niveau' => $etudiant->getNiveau(),
                'numeroCarte' => $etudiant->getNumeroCarte(),
                'solde' => $etudiant->getSolde() ? (float) $etudiant->getSolde() : 0.0,
            ],
            'message' => 'Étudiant créé avec succès'
        ], Response::HTTP_CREATED);
    }

    /**
     * Met à jour un étudiant existant
     */
    #[Route('/{id}', name: 'update', methods: ['PUT', 'PATCH'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $etudiant = $this->etudiantRepository->find($id);

        if (!$etudiant) {
            return $this->json([
                'success' => false,
                'message' => 'Étudiant non trouvé'
            ], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return $this->json([
                'success' => false,
                'message' => 'Données JSON invalides'
            ], Response::HTTP_BAD_REQUEST);
        }

        if (isset($data['nom'])) {
            $etudiant->setNom($data['nom']);
        }
        if (isset($data['prenom'])) {
            $etudiant->setPrenom($data['prenom']);
        }
        if (isset($data['email'])) {
            $etudiant->setEmail($data['email']);
        }
        if (isset($data['filiere'])) {
            $etudiant->setFiliere($data['filiere']);
        }
        if (isset($data['niveau'])) {
            $etudiant->setNiveau($data['niveau']);
        }
        if (isset($data['numeroCarte'])) {
            $etudiant->setNumeroCarte($data['numeroCarte']);
        }
        if (isset($data['solde'])) {
            $etudiant->setSolde($data['solde']);
        }

        $errors = $this->validator->validate($etudiant);
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
                'id' => $etudiant->getId(),
                'nom' => $etudiant->getNom(),
                'prenom' => $etudiant->getPrenom(),
                'email' => $etudiant->getEmail(),
                'filiere' => $etudiant->getFiliere(),
                'niveau' => $etudiant->getNiveau(),
                'numeroCarte' => $etudiant->getNumeroCarte(),
                'solde' => $etudiant->getSolde() ? (float) $etudiant->getSolde() : 0.0,
            ],
            'message' => 'Étudiant mis à jour avec succès'
        ], Response::HTTP_OK);
    }

    /**
     * Supprime un étudiant
     */
    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $etudiant = $this->etudiantRepository->find($id);

        if (!$etudiant) {
            return $this->json([
                'success' => false,
                'message' => 'Étudiant non trouvé'
            ], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($etudiant);
        $this->entityManager->flush();

        return $this->json([
            'success' => true,
            'message' => 'Étudiant supprimé avec succès'
        ], Response::HTTP_OK);
    }
}

