<?php

// src/Repository/ProductRepository.php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findExpensiveProducts(float $minPrice): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.price > :minPrice')
            ->setParameter('minPrice', $minPrice)
            ->orderBy('p.price', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
