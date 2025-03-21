<?php

namespace App\Controller;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class ApiAuthController extends AbstractController
{
    #[Route('/api/login_check', name: 'api_login', methods: ['POST'])]
    public function login(
        #[CurrentUser] ?UserInterface $user,
        JWTTokenManagerInterface $jwtManager,
    ): JsonResponse {
        if (!$user) {
            return $this->json(['error' => 'Invalid credentials'], 401);
        }

        return $this->json([
            'token' => $jwtManager->create($user),
        ]);
    }
}
