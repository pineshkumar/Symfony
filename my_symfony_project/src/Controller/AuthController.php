<?php

namespace App\Controller;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    #[Route('/api/login_check', methods: ['POST'])]
    public function login(Request $request, JWTTokenManagerInterface $jwtManager)
    {
        // Get user from the Security Context
        $user = $this->getUser();

        // If user is authenticated, generate a JWT token
        if ($user) {
            return new JsonResponse(['token' => $jwtManager->create($user)]);
        }

        return new JsonResponse(['error' => 'Invalid credentials'], 401);
    }

    #[Route('/api/register', methods: ['POST'])]
    public function register(Request $request, UserPasswordHasherInterface $hasher, EntityManagerInterface $em)
    {
        $data = json_decode($request->getContent(), true);
        $user = new User();
        $user->setEmail($data['email']);
        $user->setPassword($hasher->hashPassword($user, $data['password']));
        $user->setRoles(['ROLE_USER']);

        $em->persist($user);
        $em->flush();

        return new JsonResponse(['message' => 'User registered successfully!'], 201);
    }
}
