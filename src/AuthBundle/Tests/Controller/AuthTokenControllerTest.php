<?php

namespace AuthBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AuthTokenControllerTest extends WebTestCase
{
    public function testPostauthtokens()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/postAuthTokens');
    }

}
