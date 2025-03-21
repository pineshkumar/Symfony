<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CsrfController extends AbstractController
{
    #[Route('/csrf-token', name: 'csrf_token', methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')] // Ensures only logged-in users can access
    public function getCsrfToken(
        CsrfTokenManagerInterface $csrfTokenManager,
        RequestStack $requestStack,
    ): JsonResponse {
        $session = $requestStack->getSession();

        // Check if CSRF token is already stored in the session
        if (!$session->has('csrf_token')) {
            $csrfToken = $csrfTokenManager->getToken('authenticate')->getValue();
            $session->set('csrf_token', $csrfToken);
        }

        return new JsonResponse([
            'csrf_token' => $session->get('csrf_token'),
        ]);
    }
}
