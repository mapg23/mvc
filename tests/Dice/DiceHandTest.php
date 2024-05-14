<?php

namespace App\Dice;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Dice.
 */
class DiceHandTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties, use no arguments.
     */
    public function testCreateDiceHand(): void
    {
        $hand = new DiceHand();
        $this->assertInstanceOf("\App\Dice\DiceHand", $hand);
    }

    /**
     * Method that tests adding an dice to the hand.
     */
    public function testAdd(): void
    {
        $hand = new DiceHand();
        $die = new Dice();

        $hand->add($die);

        $this->assertEquals(1, $hand->getNumberDices());
        $this->assertContainsOnlyInstancesOf("\App\Dice\Dice", $hand->getHand());
    }

    /**
     * Method that test rolling with DiceHand.
     */
    public function testRoll(): void
    {
        $hand = new DiceHand();
        $die = new Dice();
        $hand->add($die);

        $this->assertEquals(null, $die->getValue());

        $hand->roll();

        $this->assertNotEquals([null], $hand->getValues());
    }

    /**
     * Method that tests getting the value by string.
     */
    public function testGetString(): void
    {
        $values = [];
        $hand = new DiceHand();

        $dieOne = new Dice();
        $dieOne->roll();

        $dieTwo = new Dice();
        $dieTwo->roll();

        $values[] = $dieOne->getAsString();
        $values[] = $dieTwo->getAsString();

        $hand->add($dieOne);
        $hand->add($dieTwo);

        $this->assertEqualsCanonicalizing($values, $hand->getString());
    }
}
