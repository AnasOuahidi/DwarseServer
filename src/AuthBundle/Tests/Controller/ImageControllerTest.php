<?php

namespace AuthBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ImageControllerTest extends WebTestCase
{
    public function testHeart()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/heart');
    }

    public function testLogo()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/logo');
    }

}
