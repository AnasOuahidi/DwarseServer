<?php

namespace Tests\AuthBundle\Controller;

use AuthBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AuthTokenControllerTest extends WebTestCase {
    protected $em;
    protected $encoder;

    public function setUp() {
        parent::setUp();
        self::bootKernel();

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $this->encoder = static::$kernel->getContainer()->get('security.password_encoder');
    }

    public function testConfirmationSansToken(){
        $client = $this->createClient();
        $countTokens = count($this->em->getRepository('AuthBundle:AuthToken')->findAll());
        $client->request(
            'POST',
            '/auth/authtokens',
            [],
            [],
            [
                'HTTP_Accept' => 'application/json',
                'HTTP_Content-Type' => 'application/json'
            ]
        );
        $response = $client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
        $tokens = $this->em->getRepository('AuthBundle:AuthToken')->findAll();
        $this->assertEquals($countTokens, count($tokens));
    }

    public function testConfirmationAvecTokenIncorrect(){
        $client = $this->createClient();
        $countTokens = count($this->em->getRepository('AuthBundle:AuthToken')->findAll());
        $client->request(
            'POST',
            '/auth/authtokens',
            [
                'token' => $this->generateToken(64)
            ],
            [],
            [
                'HTTP_Accept' => 'application/json',
                'HTTP_Content-Type' => 'application/json'
            ]
        );
        $response = $client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
        $tokens = $this->em->getRepository('AuthBundle:AuthToken')->findAll();
        $this->assertEquals($countTokens, count($tokens));
    }

    public function testConfirmationCompteDejaConfirme(){
        $user = new User();
        $token = $this->generateToken(64);
        $user->setEmail('testing@test.com');
        $user->setLogin('testing3');
        $user->setRole('ROLE_COMMERCANT');
        $encoded = $this->encoder->encodePassword($user, 'testing3');
        $user->setPassword($encoded);
        $user->setConfirmed(true);
        $user->setConfirmationToken($token);
        $this->em->persist($user);
        $this->em->flush();
        $client = $this->createClient();
        $countTokens = count($this->em->getRepository('AuthBundle:AuthToken')->findAll());
        $client->request(
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
        $response = $client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
        $tokens = $this->em->getRepository('AuthBundle:AuthToken')->findAll();
        $this->assertEquals($countTokens, count($tokens));
        $newUser = $this->em->getRepository('AuthBundle:User')->find($user->getId());
        $this->assertTrue($newUser->getConfirmed());
        $this->assertNull($newUser->getConfirmationToken());
    }

    public function testConfirmationCompte(){
        $user = new User();
        $token = $this->generateToken(64);
        $user->setEmail('testing4@test.com');
        $user->setLogin('testing4');
        $user->setRole('ROLE_COMMERCANT');
        $encoded = $this->encoder->encodePassword($user, 'testing4');
        $user->setPassword($encoded);
        $user->setConfirmed(false);
        $user->setConfirmationToken($token);
        $this->em->persist($user);
        $this->em->flush();
        $client = $this->createClient();
        $countTokens = count($this->em->getRepository('AuthBundle:AuthToken')->findAll());
        $client->request(
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
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $tokens = $this->em->getRepository('AuthBundle:AuthToken')->findAll();
        $this->assertEquals($countTokens + 1, count($tokens));
        $arrayResponse = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('authToken', $arrayResponse);
        $this->assertEquals(64, strlen($arrayResponse['authToken']['value']));
        $newUser = $this->em->getRepository('AuthBundle:User')->find($user->getId());
        $this->assertTrue($newUser->getConfirmed());
        $this->assertNull($newUser->getConfirmationToken());
        $this->assertEquals(64, strlen($newUser->getAuthToken()->getValue()));
    }

    private function generateToken($length) {
        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $string = '';
        $max = strlen($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[mt_rand(0, $max)];
        }
        return $string;
    }
}
