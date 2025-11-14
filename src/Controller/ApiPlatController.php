<?php

namespace App\Controller;

use App\Entity\Plat;
use App\Repository\PlatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/plats', name: 'api_plat_')]
final class ApiPlatController extends AbstractController
{
    public function __construct(
        private PlatRepository $platRepository,
        private EntityManagerInterface $entityManager,
        private SerializerInterface $serializer,
        private ValidatorInterface $validator
    ) {
    }

    /**
     * Récupère la liste de tous les plats
     */
    #[Route('', name: 'list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $plats = $this->platRepository->findAll();
        
        $data = array_map(function (Plat $plat) {
            return [
                'id' => $plat->getId(),
                'nom' => $plat->getNom(),
                'description' => $plat->getDescription(),
                'prix' => (float) $plat->getPrix(),
                'categorie' => $plat->getCategorie(),
                'image' => $plat->getImage(),
            ];
        }, $plats);

        return $this->json([
            'success' => true,
            'data' => $data,
            'message' => 'Liste des plats récupérée avec succès'
        ], Response::HTTP_OK);
    }

    /**
     * Récupère les détails d'un plat
     */
    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $plat = $this->platRepository->find($id);

        if (!$plat) {
            return $this->json([
                'success' => false,
                'message' => 'Plat non trouvé'
            ], Response::HTTP_NOT_FOUND);
        }

        $data = [
            'id' => $plat->getId(),
            'nom' => $plat->getNom(),
            'description' => $plat->getDescription(),
            'prix' => (float) $plat->getPrix(),
            'categorie' => $plat->getCategorie(),
            'image' => $plat->getImage(),
        ];

        return $this->json([
            'success' => true,
            'data' => $data,
            'message' => 'Plat récupéré avec succès'
        ], Response::HTTP_OK);
    }

    /**
     * Crée un nouveau plat
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

        $plat = new Plat();
        $plat->setNom($data['nom'] ?? '');
        $plat->setDescription($data['description'] ?? null);
        $plat->setPrix($data['prix'] ?? '0.00');
        $plat->setCategorie($data['categorie'] ?? '');
        $plat->setImage($data['image'] ?? null);

        $errors = $this->validator->validate($plat);
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

        $this->entityManager->persist($plat);
        $this->entityManager->flush();

        return $this->json([
            'success' => true,
            'data' => [
                'id' => $plat->getId(),
                'nom' => $plat->getNom(),
                'description' => $plat->getDescription(),
                'prix' => (float) $plat->getPrix(),
                'categorie' => $plat->getCategorie(),
                'image' => $plat->getImage(),
            ],
            'message' => 'Plat créé avec succès'
        ], Response::HTTP_CREATED);
    }

    /**
     * Met à jour un plat existant
     */
    #[Route('/{id}', name: 'update', methods: ['PUT', 'PATCH'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $plat = $this->platRepository->find($id);

        if (!$plat) {
            return $this->json([
                'success' => false,
                'message' => 'Plat non trouvé'
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
            $plat->setNom($data['nom']);
        }
        if (isset($data['description'])) {
            $plat->setDescription($data['description']);
        }
        if (isset($data['prix'])) {
            $plat->setPrix($data['prix']);
        }
        if (isset($data['categorie'])) {
            $plat->setCategorie($data['categorie']);
        }
        if (isset($data['image'])) {
            $plat->setImage($data['image']);
        }

        $errors = $this->validator->validate($plat);
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
                'id' => $plat->getId(),
                'nom' => $plat->getNom(),
                'description' => $plat->getDescription(),
                'prix' => (float) $plat->getPrix(),
                'categorie' => $plat->getCategorie(),
                'image' => $plat->getImage(),
            ],
            'message' => 'Plat mis à jour avec succès'
        ], Response::HTTP_OK);
    }

    /**
     * Supprime un plat
     */
    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $plat = $this->platRepository->find($id);

        if (!$plat) {
            return $this->json([
                'success' => false,
                'message' => 'Plat non trouvé'
            ], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($plat);
        $this->entityManager->flush();

        return $this->json([
            'success' => true,
            'message' => 'Plat supprimé avec succès'
        ], Response::HTTP_OK);
    }

    /**
     * Récupère les plats par catégorie
     */
    #[Route('/categorie/{categorie}', name: 'by_categorie', methods: ['GET'])]
    public function byCategorie(string $categorie): JsonResponse
    {
        $plats = $this->platRepository->findBy(['categorie' => $categorie]);

        $data = array_map(function (Plat $plat) {
            return [
                'id' => $plat->getId(),
                'nom' => $plat->getNom(),
                'description' => $plat->getDescription(),
                'prix' => (float) $plat->getPrix(),
                'categorie' => $plat->getCategorie(),
                'image' => $plat->getImage(),
            ];
        }, $plats);

        return $this->json([
            'success' => true,
            'data' => $data,
            'message' => 'Plats de la catégorie récupérés avec succès'
        ], Response::HTTP_OK);
    }
}

