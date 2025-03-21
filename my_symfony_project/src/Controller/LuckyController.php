<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LuckyController extends AbstractController // Extend AbstractController
{
    #[Route('/lucky/numbers')]
    public function number(): Response
    {
        $number = random_int(0, 100);

        return new Response(
            '<html><body>Lucky number: ' . $number . '</body></html>'
        );
    }

    #[Route('/lucky/words')]
    public function restdata(): Response
    {
        return $this->render('home/index.html.twig', [
            'message' => 'Welcome to your First Symfony Page',
        ]);
    }
}
