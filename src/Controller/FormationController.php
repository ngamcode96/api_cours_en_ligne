<?php

namespace App\Controller;

use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class FormationController extends AbstractController
{
    #[Route('/api/formations', name: 'app_formations')]
    public function index(FormationRepository $formationRepository): JsonResponse
    {
        return $this->json([
            "formations" => $formationRepository->findAll()
        ]);
    }

    #[Route('/api/formations/new', name: 'new_formation')]
    #[IsGranted('ROLE_USER', message: 'Vous n\'avez pas les droits suffisants pour créer une formation. Connectez-vous')]
    public function new_formation(TokenStorageInterface $tokenStorageInterface, JWTTokenManagerInterface $jwtManager): JsonResponse
    {
        $decodedJwtToken = $jwtManager->decode($tokenStorageInterface->getToken());
        return $this->json([
            'message' => 'Welcome to your new formation!',
            'path' => $decodedJwtToken
        ]);
    }
}
