<?php

namespace App\Tests\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserSignupTest extends WebTestCase
{
    public function testUserSignup()
    {
        $client = static::createClient();

        $client->request('POST', '/api/signup', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'email' => 'user1@example.com',
            'password' => 'password123',
            'roles' => ['ROLE_USER'],
        ]));

        $this->assertResponseStatusCodeSame(201);
    }

    public function testDuplicateSignupFails()
    {
        $client = static::createClient();

        $client->request('POST', '/api/signup', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'email' => 'user1@example.com',
            'password' => 'password123',
            'roles' => ['ROLE_USER'],
        ]));

        $this->assertResponseStatusCodeSame(400);
    }
}
