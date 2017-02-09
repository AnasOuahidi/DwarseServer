<?php

namespace AuthBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testPostusers()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/users');
    }

}
