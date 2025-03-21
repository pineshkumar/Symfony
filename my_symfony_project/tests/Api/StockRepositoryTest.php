<?php

namespace App\Tests\Integration;

use App\Entity\Stock;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class StockRepositoryTest extends KernelTestCase
{
    private ?EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
    }

    public function testStockDatabaseInsertion()
    {
        $stock = new Stock();
        $stock->setPrice(100);
        $stock->setNote('Test Stock');

        $this->entityManager->persist($stock);
        $this->entityManager->flush();

        $stockRepo = $this->entityManager->getRepository(Stock::class);
        $retrievedStock = $stockRepo->findOneBy(['price' => 100]);

        $this->assertNotNull($retrievedStock);
        $this->assertEquals('Test Stock', $retrievedStock->getNote());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
