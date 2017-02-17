<?php

namespace Tests\AuthBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginControllerTest extends WebTestCase {
    protected $em;

    public function setUp() {
        parent::setUp();
        self::bootKernel();

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testAuthentificationSansLoginEtPassword() {
        $client = $this->createClient();
        $client->request(
            'POST',
            '/auth/login',
            [],
            [],
            [
                'HTTP_Accept' => 'application/json',
                'HTTP_Content-Type' => 'application/json'
            ]
        );
        $response = $client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testAuthentificationAvecLoginEtPasswordIncorrects() {
        $client = $this->createClient();
        $client->request(
            'POST',
            '/auth/login',
            [
                "login" => "employeur",
                "password" => "donnesbidon"
            ],
            [],
            [
                'HTTP_Accept' => 'application/json',
                'HTTP_Content-Type' => 'application/json'
            ]
        );
        $response = $client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testAuthentificationAvecCompteNonConfirme() {
        $client = $this->createClient();
        $client->request(
            'POST',
            '/auth/users',
            [
                "login" => "testing10",
                "email" => "testing10@testing.com",
                "plainPassword" => "testing10",
                "role" => "ROLE_EMPLOYEUR"
            ],
            [],
            [
                'HTTP_Accept' => 'application/json',
                'HTTP_Content-Type' => 'application/json'
            ]
        );
        $response = $client->getResponse();
        $this->assertEquals(201, $response->getStatusCode());
        $client2 = $this->createClient();
        $client2->request(
            'POST',
            '/auth/login',
            [
                "login" => "testing10",
                "password" => "testing10"
            ],
            [],
            [
                'HTTP_Accept' => 'application/json',
                'HTTP_Content-Type' => 'application/json'
            ]
        );
        $response2 = $client2->getResponse();
        $this->assertEquals(400, $response2->getStatusCode());
    }

    public function testAuthentificationAvecCompteConfirme() {
        $client = $this->createClient();
        $client->request(
            'POST',
            '/auth/users',
            [
                "login" => "testing11",
                "email" => "testing11@testing.com",
                "plainPassword" => "testing11",
                "role" => "ROLE_EMPLOYEUR"
            ],
            [],
            [
                'HTTP_Accept' => 'application/json',
                'HTTP_Content-Type' => 'application/json'
            ]
        );
        $response = $client->getResponse();
        $this->assertEquals(201, $response->getStatusCode());
        $arrayResponse1 = json_decode($response->getContent(), true);
        $token = $arrayResponse1['confirmationToken'];
        $client2 = $this->createClient();
        $client2->request(
            'POST',
            '/auth/authtokens',
            [
                'token' => $token
            ],
            [],
            [
                'HTTP_Accept' => 'application/json',
                'HTTP_Content-Type' => 'application/json'
            ]
        );
        $response2 = $client2->getResponse();
        $this->assertEquals(200, $response2->getStatusCode());
        $client3 = $this->createClient();
        $client3->request(
            'POST',
            '/auth/login',
            [
                "login" => "testing11",
                "password" => "testing11"
            ],
            [],
            [
                'HTTP_Accept' => 'application/json',
                'HTTP_Content-Type' => 'application/json'
            ]
        );
        $response3 = $client3->getResponse();
        $this->assertEquals(200, $response3->getStatusCode());
        $arrayResponse = json_decode($response3->getContent(), true);
        $this->assertTrue($arrayResponse['confirmed']);
        $this->assertNull($arrayResponse['confirmationToken']);
        $this->assertArrayHasKey('authToken', $arrayResponse);
        $this->assertArrayHasKey('value', $arrayResponse['authToken']);
        $this->assertEquals(64, strlen($arrayResponse['authToken']['value']));
    }
}
