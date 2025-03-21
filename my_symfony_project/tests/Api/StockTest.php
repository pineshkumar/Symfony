<?php

namespace App\Tests\Unit;

use PHPUnit\Framework\TestCase;

class StockTest extends TestCase
{
    public function testCalculateTotalPrice()
    {
        $price = 100;
        $quantity = 5;
        $totalPrice = $price * $quantity;

        // Check if total price is correct
        $this->assertEquals(500, $totalPrice);
    }

    public function testApplyDiscount()
    {
        $price = 200;
        $discount = 10; // 10% discount
        $finalPrice = $price - ($price * ($discount / 100));

        // Check if discount is applied correctly
        $this->assertEquals(180, $finalPrice);
    }
}
