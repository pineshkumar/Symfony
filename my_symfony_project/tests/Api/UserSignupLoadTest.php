<?php

namespace App\Tests\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserSignupLoadTest extends WebTestCase
{
    public function testMultipleUserSignup()
    {
        $client = static::createClient();
        $users = [];

        // Generate 10 unique users for load testing
        for ($i = 1; $i <= 10; $i++) {
            $users[] = [
                'email' => "testuser$i@example.com",
                'password' => 'password123',
                'roles' => ['ROLE_USER']
            ];
        }

        foreach ($users as $user) {
            $client->request('POST', '/api/signup', [], [], [
                'CONTENT_TYPE' => 'application/json',
            ], json_encode($user));

            $response = $client->getResponse();
            $this->assertResponseStatusCodeSame(201, "Signup failed for: {$user['email']}");
        }
    }

    public function testDuplicateUserSignupFails()
    {
        $client = static::createClient();

        $userData = [
            'email' => 'duplicate@example.com',
            'password' => 'password123',
            'roles' => ['ROLE_USER']
        ];

        // First request should succeed
        $client->request('POST', '/api/signup', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode($userData));

        $this->assertResponseStatusCodeSame(201);

        // Second request with same email should fail
        $client->request('POST', '/api/signup', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode($userData));

        $this->assertResponseStatusCodeSame(400, 'Duplicate email should not be allowed');
    }
}
