<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class LoginController extends AbstractController
{
    #[Route('/login_check', name: 'login', methods: ['GET', 'POST'])]
    public function login(
        Request $request,
        SessionInterface $session,
        CsrfTokenManagerInterface $csrfTokenManager,
    ): Response {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $password = $request->request->get('password');
            $csrfToken = $request->request->get('_csrf_token');

            // Validate CSRF token
            if (!$csrfTokenManager->isTokenValid(new CsrfToken('authenticate', $csrfToken))) {
                $this->addFlash('error', 'Invalid CSRF token.');

                return $this->redirectToRoute('login');
            }

            // Call API to authenticate user
            $apiUrl = 'http://my_symfony_project.lndo.site/api/login';
            $postData = json_encode([
                'email' => $email,
                'password' => $password,
            ]);

            $ch = curl_init($apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if (200 === $httpCode) {
                $responseData = json_decode($response, true);
                $jwtToken = $responseData['token'] ?? null;

                if ($jwtToken) {
                    // Store token in session
                    $session->set('jwt_token', $jwtToken);

                    return $this->redirectToRoute('dashboard');
                }
            }

            $this->addFlash('error', 'Invalid credentials, please try again.');
        }

        return $this->render('login.html.twig');
    }
}
