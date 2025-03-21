<?php

namespace App\Tests\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginApiTest extends WebTestCase
{
    public function testLoginWithValidCredentials()
    {
        $client = static::createClient();

        $client->request('POST', '/api/login_check', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'email' => 'test@test.com',
            'password' => '12345',
        ]));

        // Capture Response
        $responseContent = $client->getResponse()->getContent();
        $statusCode = $client->getResponse()->getStatusCode();

        // Debug: Print response content and status code
        echo "\nLogin API Response: " . $responseContent . "\n";
        echo 'Status Code: ' . $statusCode . "\n";

        // Decode JSON response
        $data = json_decode($responseContent, true);

        // If decoding fails, print error message
        if (null === $data) {
            echo "\nJSON Decode Error: " . json_last_error_msg() . "\n";
        }

        // Ensure response is a valid JSON array
        $this->assertIsArray($data, 'Login response is not a valid JSON array');

        // Ensure token exists
        $this->assertArrayHasKey('token', $data, 'JWT token was not returned in login response');

        // Ensure HTTP status is 200
        $this->assertResponseStatusCodeSame(200);
    }
}
