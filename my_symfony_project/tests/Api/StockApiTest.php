<?php

namespace App\Tests\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class StockApiTest extends WebTestCase
{
    public function testCreateStockUnauthorized()
    {
        $client = static::createClient();
        $client->request('POST', '/api/stock/new', [], [], [
        'CONTENT_TYPE' => 'application/json',
        ], json_encode([
        'price' => 500,
        'note' => 'New stock item',
        ]));

        $this->assertResponseStatusCodeSame(401); // Unauthorized
    }

    public function testCreateStockAuthorized()
    {
        $client = static::createClient();

      // Send login request
        $client->request('POST', '/api/login_check', [], [], [
        'CONTENT_TYPE' => 'application/json',
        ], json_encode([
        'email' => 'test@test.com',
        'password' => '12345',
        ]));

      // Capture Response
        $responseContent = $client->getResponse()->getContent();

      // Debug: Print response content to check what was returned
        echo "\nLogin API Response: " . $responseContent . "\n";

      // Decode response
        $data = json_decode($responseContent, true);

      // If decoding fails, print error message
        if (null === $data) {
            echo "\nJSON Decode Error: " . json_last_error_msg() . "\n";
        }

      // Ensure token exists before proceeding
        $this->assertIsArray($data, 'Login response is not a valid JSON array');
        $this->assertArrayHasKey('token', $data, 'JWT token was not returned in login response');

        $token = $data['token'];

      // Create Stock
        $client->request('POST', '/api/stock/new', [], [], [
        'CONTENT_TYPE' => 'application/json',
        'HTTP_AUTHORIZATION' => 'Bearer ' . $token,
        ], json_encode([
        'price' => 500,
        'note' => 'New stock item',
        ]));

        $this->assertResponseStatusCodeSame(201);
    }
}
