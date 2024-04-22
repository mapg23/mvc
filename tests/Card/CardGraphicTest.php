<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

class CardGraphicTest extends TestCase
{
    public function testCreatedProperly(): void
    {
        $card = new CardGraphic("diamond", 2);

        $this->assertInstanceOf("\App\Card\CardGraphic", $card);

        $this->assertEquals("diamond", $card->getType());
        $this->assertEquals(2, $card->getValue());
    }

    public function testGetAsString(): void
    {
        $card = new CardGraphic("diamonds", 2);

        $this->assertInstanceOf("\App\Card\CardGraphic", $card);

        $this->assertEquals('🃂', $card->getAsString());

    }
}
