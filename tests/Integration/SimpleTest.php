<?php

namespace App\Tests;

use Symfony\Component\Panther\PantherTestCase;

class SimpleTest extends PantherTestCase
{
    public function testMyApp(): void
    {
        $client = static::createPantherClient(); // Your app is automatically started using the built-in web server
        $client->request('GET', '/mypage');

        // Use any PHPUnit assertion, including the ones provided by Symfony
        $this->assertPageTitleContains('My Title');
        $this->assertSelectorTextContains('#main', 'My body');
    }
}