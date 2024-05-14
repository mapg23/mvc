<?php

namespace App\Dice;

use PHPUnit\Framework\TestCase;

class DiceGraphicsTest extends TestCase
{
    public function testCreatedProperly(): void
    {
        $die = new DiceGraphic();
        $this->assertInstanceOf("\App\Dice\DiceGraphic", $die);

    }

    public function testGetAsString(): void
    {
        $die = new DiceGraphic();
        $die->setValue(1);

        $this->assertEquals('⚀', $die->getAsString());
    }
}
