<?php

namespace App\Project;

use App\Project\Hand;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Test cases for class Card.
 */
class CardTest extends TestCase
{
    public function testCreated(): void
    {
        $card = new Card("diamonds", 2);

        $this->assertInstanceOf("\App\Project\Card", $card);
    }

    public function testGetImage(): void
    {
        $card = new Card("diamonds", 2);

        $this->assertEquals("2_of_diamonds.png", $card->getImage());
    }
}
