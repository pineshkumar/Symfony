<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'dashboard')]
    public function index(SessionInterface $session): Response
    {
        $jwtToken = $session->get('jwt_token');

        if (!$jwtToken) {
            return $this->redirectToRoute('login');
        }

        // Fetch protected API data
        $apiUrl = 'http://my_symfony_project.lndo.site/api/protected';

        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $jwtToken,
            'Accept: application/json',
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        return $this->render('dashboard.html.twig', [
            'data' => json_decode($response, true),
        ]);
    }
}
