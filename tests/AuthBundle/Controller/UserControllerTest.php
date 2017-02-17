<?php

namespace Tests\AuthBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase {
    protected $em;

    public function setUp() {
        parent::setUp();
        self::bootKernel();

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();

    }

    public function testInscriptionAvecFormVide() {
        $client = $this->createClient();
        $countUsers = count($this->em->getRepository('AuthBundle:User')->findAll());
        $client->request(
            'POST',
            '/auth/users',
            [],
            [],
            [
                'HTTP_Accept' => 'application/json',
                'HTTP_Content-Type' => 'application/json'
            ]
        );
        $response = $client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
        $users = $this->em->getRepository('AuthBundle:User')->findAll();
        $this->assertEquals($countUsers, count($users));
    }

    public function testInscriptionAvecFormInValide() {
        $client = $this->createClient();
        $countUsers = count($this->em->getRepository('AuthBundle:User')->findAll());
        $client->request(
            'POST',
            '/auth/users',
            [
                "login" => "testing",
                "email" => "testing@testing.com",
                "plainPassword" => "testing"
            ],
            [],
            [
                'HTTP_Accept' => 'application/json',
                'HTTP_Content-Type' => 'application/json'
            ]
        );
        $response = $client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
        $users = $this->em->getRepository('AuthBundle:User')->findAll();
        $this->assertEquals($countUsers, count($users));
    }

    public function testInscriptionAvecMemeLogin() {
        $client = $this->createClient();
        $countUsers = count($this->em->getRepository('AuthBundle:User')->findAll());
        $client->request(
            'POST',
            '/auth/users',
            [
                "login" => "testing",
                "email" => "test@test.com",
                "plainPassword" => "testing",
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
        $users = $this->em->getRepository('AuthBundle:User')->findAll();
        $this->assertEquals($countUsers + 1, count($users));
        $arrayResponse = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('confirmationToken', $arrayResponse);
        $this->assertArrayHasKey('confirmed', $arrayResponse);
        $this->assertArrayHasKey('authToken', $arrayResponse);
        $this->assertEquals(64, strlen($arrayResponse['confirmationToken']));
        $this->assertFalse($arrayResponse['confirmed']);
        $this->assertNull($arrayResponse['authToken']);
        $user = $this->em->getRepository('AuthBundle:User')->find($arrayResponse['id']);
        $this->assertEquals(60, strlen($user->getPassword()));
        $client2 = $this->createClient();
        $countNewUsers = count($this->em->getRepository('AuthBundle:User')->findAll());
        $client2->request(
            'POST',
            '/auth/users',
            [
                "login" => "testing",
                "email" => "test@test.com",
                "plainPassword" => "testing",
                "role" => "ROLE_COMMERCANT"
            ],
            [],
            [
                'HTTP_Accept' => 'application/json',
                'HTTP_Content-Type' => 'application/json'
            ]
        );
        $newUsers = $this->em->getRepository('AuthBundle:User')->findAll();
        $newResponse = $client2->getResponse();
        $this->assertEquals(400, $newResponse->getStatusCode());
        $this->assertEquals($countNewUsers, count($newUsers));
    }

    public function testInscriptionEnvoiDeMail() {
        $client = static::createClient();
        $client->enableProfiler();
        $client->request(
            'POST',
            '/auth/users',
            [
                "login" => "testing2",
                "email" => "test2@test.com",
                "plainPassword" => "testing",
                "role" => "ROLE_COMMERCANT"
            ],
            [],
            [
                'HTTP_Accept' => 'application/json',
                'HTTP_Content-Type' => 'application/json'
            ]
        );
        $response = $client->getResponse();
        $this->assertEquals(201, $response->getStatusCode());
        $mailCollector = $client->getProfile()->getCollector('swiftmailer');
        $this->assertEquals(1, $mailCollector->getMessageCount());
        $collectedMessages = $mailCollector->getMessages();
        $message = $collectedMessages[0];
        $this->assertInstanceOf('Swift_Message', $message);
        $this->assertEquals('Confirmation de compte', $message->getSubject());
        $this->assertEquals('dwarse.development@gmail.com', key($message->getFrom()));
        $this->assertEquals('Dwarse Team', $message->getFrom()[key($message->getFrom())]);
        $this->assertEquals('test2@test.com', key($message->getTo()));
    }
}
