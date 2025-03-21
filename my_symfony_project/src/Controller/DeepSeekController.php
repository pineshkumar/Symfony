<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeepSeekController extends AbstractController
{
  /**
   * @Route("/deepseek", name="deepseek")
   */
  public function index(): Response
  {
    return $this->render('deepseek.html.twig');
  }
}