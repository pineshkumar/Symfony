<?php

// src/Controller/UserController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class UserController extends AbstractController
{
    #[Route('/api/user', methods: ['GET'])]
    public function getUserProfile(UserInterface $user)
    {
        return new JsonResponse([
            'id' => $user->getId(),
            'email' => $user->getUserIdentifier(),
        ]);
    }
}
