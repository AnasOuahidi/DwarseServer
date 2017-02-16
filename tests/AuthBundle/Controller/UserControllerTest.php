<?php

namespace Tests\AuthBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\getAllApiTokensTrait;

class UserControllerTest extends WebTestCase {
    use getAllApiTokensTrait;

    public function testPostusers() {
    }

    public function testGetusers() {
        $client = $this->createClient();
        $client->request(
            'GET',
            '/auth/users',
            [],
            [],
            [
                'HTTP_X-Auth-Token' => $this->tokenEmploye,
                'HTTP_Accept' => 'application/json',
                'HTTP_Content-Type' => 'application/json'
            ]
        );
        $response = $client->getResponse();
        $arrayResponse = json_decode($response->getContent(), true);
        $this->assertInternalType('array', $arrayResponse);
        $this->assertEquals(3, count($arrayResponse));
    }
}
