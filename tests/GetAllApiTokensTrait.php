<?php

namespace Tests;

trait getAllApiTokensTrait {
    protected $em;
    protected $tokenEmploye;
    protected $tokenEmployeur;
    protected $tokenCommercant;

    public function setUp() {
        parent::setUp();
        self::bootKernel();

        $this->em = static::$kernel->getContainer()->get('doctrine')->getManager();
        $this->tokenEmploye = $this->getToken('employe', 'employe');
        $this->tokenEmployeur = $this->getToken('employeur', 'employeur');
        $this->tokenCommercant = $this->getToken('commercant', 'commercant');
    }

    public function getToken($login, $password) {
        $client = $this->createClient();
        $client->request(
            'POST',
            '/auth/login',
            ['login' => $login, 'password' => $password],
            [],
            [
                'HTTP_Accept' => 'application/json',
                'HTTP_Content-Type' => 'application/json'
            ]
        );
        $response = $client->getResponse();
        $arrayResponse = json_decode($response->getContent(), true);
        return $arrayResponse['authToken']['value'];
    }
}
