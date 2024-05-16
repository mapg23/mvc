<?php

namespace App\Dice;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Dice.
 */
class DiceTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties, use no arguments.
     */
    public function testCreateDice(): void
    {
        $die = new Dice();
        $this->assertInstanceOf("\App\Dice\Dice", $die);

        $res = $die->getAsString();
        $this->assertNotEmpty($res);
    }

    /**
     * Method that tests if roll, rolls correctly.
     *
     * @return void
     */
    public function testRoll(): void
    {
        $die = new Dice();

        $die->roll();

        $this->assertGreaterThanOrEqual(1, $die->getValue());
        $this->assertLessThanOrEqual(6, $die->getValue());
    }
}
