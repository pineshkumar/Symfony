<?php

namespace App\Controller;

use App\Entity\Stock;
use App\Repository\StockRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/stock')]
class StockController extends AbstractController
{
    #[Route('/', name: 'api_stock_index', methods: ['GET'])]
    public function index(StockRepository $stockRepository): JsonResponse
    {
        $stocks = $stockRepository->findAll();

        return $this->json($stocks);
    }

    #[Route('/new', name: 'api_stock_new', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $stock = new Stock();
        $stock->setPrice($data['price']);
        $stock->setNote($data['note']);

        $entityManager->persist($stock);
        $entityManager->flush();

        return $this->json(['message' => 'Stock created successfully!', 'id' => $stock->getId()], 201);
    }

    #[Route('/{id}', name: 'api_stock_show', methods: ['GET'])]
    public function show(Stock $stock): JsonResponse
    {
        return $this->json($stock);
    }

    #[Route('/{id}/edit', name: 'api_stock_edit', methods: ['PUT', 'PATCH'])]
    #[IsGranted('ROLE_USER')]
    public function edit(Request $request, Stock $stock, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (isset($data['price'])) {
            $stock->setPrice($data['price']);
        }
        if (isset($data['note'])) {
            $stock->setNote($data['note']);
        }

        $entityManager->flush();

        return $this->json(['message' => 'Stock updated successfully!']);
    }

    #[Route('/{id}', name: 'api_stock_delete', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Stock $stock, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($stock);
        $entityManager->flush();

        return $this->json(['message' => 'Stock deleted successfully!']);
    }
}
