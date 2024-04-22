<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Card.
 */
class CardTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties, use no arguments.
     */
    public function testGetType(): void
    {
        $card = new Card("diamond", 2);
        $this->assertInstanceOf("\App\Card\Card", $card);

        $this->assertEquals("diamond", $card->getType());
    }

    /**
     * Construct object and verify that the object has the expected
     * properties, use no arguments.
     */
    public function testGetValue(): void
    {
        $card = new Card("diamond", 2);
        $this->assertInstanceOf("\App\Card\Card", $card);

        $this->assertEquals(2, $card->getValue());
    }
}
