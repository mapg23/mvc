<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;


class CardHandTest extends TestCase
{

    public function testCardHandCreate()
    {
        $hand = array(
            new CardGraphic("diamonds", 1),
            new CardGraphic("diamonds", 2),
        );

        $emptyHand = new CardHand();
        $cardHand = new CardHand($hand);
        
        $this->assertInstanceOf("\App\Card\CardHand", $cardHand);
        $this->assertInstanceOf("\App\Card\CardHand", $emptyHand);

        $this->assertEqualsCanonicalizing([], $emptyHand->getHand());
        $this->assertEqualsCanonicalizing($hand, $cardHand->getHand());
    }

    public function testAddCardToHand()
    {
        $hand = array(
            new CardGraphic("diamonds", 1),
            new CardGraphic("diamonds", 2),
        );

        $cardHand = new CardHand();

        $cardHand->add($hand);

        $this->assertEqualsCanonicalizing($hand, $cardHand->getHand());
    }

    public function testGetHand()
    {
        $cardHand = new CardHand();

        $this->assertEqualsCanonicalizing([], $cardHand->getHand());
    }

    public function testSetHand()
    {
        $hand = array(
            new CardGraphic("diamonds", 1),
            new CardGraphic("diamonds", 2),
        );

        $cardHand = new CardHand();

        $this->assertEqualsCanonicalizing([], $cardHand->getHand());

        $cardHand->setHand($hand);

        $this->assertEqualsCanonicalizing($hand, $cardHand->getHand());
    }

    public function testHasStoped()
    {
        $cardHand = new CardHand();

        $this->assertIsBool($cardHand->hasStoped());
        $this->assertFalse($cardHand->hasStoped());
        $cardHand->setStand(true);
        $this->assertTrue($cardHand->hasStoped());
    }

    public function testGetScore()
    {
        $cardHand = new CardHand(array(new CardGraphic("diamonds", 2)));
        
        $this->assertIsInt($cardHand->getScore());
        $this->assertEquals(2, $cardHand->getScore());
    }

    public function testCalculateScore()
    {
        $hand = array(
            new CardGraphic("diamonds", 1),
            new CardGraphic("diamonds", 11),
            new CardGraphic("clubs", 5)
        );

        $cardHand = new CardHand($hand);

        $this->assertIsInt($cardHand->calculateScore());
        $this->assertEquals(16, $cardHand->calculateScore());
    }

    public function testGetStop()
    {
        $cardHand = new CardHand();

        $this->assertFalse($cardHand->getStop());
    }
}
