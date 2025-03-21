<?php

namespace App\Tests\Service;

use App\Service\StockService;
use PHPUnit\Framework\TestCase;

class StockServiceTest extends TestCase
{
    public function testCalculateDiscount()
    {
        $stockService = new StockService();

        $price = 100;
        $quantity = 10;
        $discountedPrice = $stockService->calculateDiscount($price, $quantity);

        $this->assertEquals(90, $discountedPrice); // 10% discount expected
    }
}
