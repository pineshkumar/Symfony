<?php

namespace App\Tests\Performance;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class StockPerformanceTest extends WebTestCase
{
    private string $externalApiUrl = 'http://localhost:11434/api/generate';

    public function testStockApiResponseTime()
    {
        $client = static::createClient();
        $startTime = microtime(true);

        $client->request('GET', '/api/stock');
        $responseTime = microtime(true) - $startTime;
        $responseContent = $client->getResponse()->getContent();

        dump("Stock API Response Time: " . $responseTime . " seconds");
        dump("Stock API Response: " . $responseContent);

        // Assert response time is within 300ms
        $this->assertLessThan(0.3, $responseTime, 'Stock API took too long to respond!');
    }

    public function testExternalGenerateApiResponseTime()
    {
        $client = static::createClient();
        $startTime = microtime(true);

        // Send request to the external API directly
        $client->request('POST', $this->externalApiUrl, [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            "model" => "deepseek-r1:1.5b",
            "prompt" => "Explain how AI works.",
            "stream" => false
        ]));

        $responseTime = microtime(true) - $startTime;
        $responseContent = $client->getResponse()->getContent();

        dump("Generate API Response Time: " . $responseTime . " seconds");
        dump("Generate API Full URL: " . $this->externalApiUrl);
        dump("Generate API Response: " . $responseContent);

        // Assert response time is within 500ms (adjust if needed)
        $this->assertLessThan(0.5, $responseTime, 'External Generate API took too long to respond!');

        // Shell command to check the response directly
        $shellResponse = shell_exec('curl -s -o /dev/null -w "%{http_code}" ' . $this->externalApiUrl);
        dump("Shell Command Response: " . $shellResponse);
    }
}
