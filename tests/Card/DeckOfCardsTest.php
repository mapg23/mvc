<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Card.
 */
class DeckOfCardsTest extends TestCase
{
    public function testCreationOfDeck()
    {
        $deck = new DeckOfCards();

        $this->assertInstanceOf("\App\Card\DeckOfCards", $deck);

        $this->assertContainsOnlyInstancesOf("\App\Card\CardGraphic", $deck->getDeck());
    }

    public function testDrawCards()
    {
        $deck = new DeckOfCards();

        $this->assertEquals(52, $deck->getDeckSize());

        $newCard = $deck->drawCard();

        $this->assertEquals(51, $deck->getDeckSize());
        $this->assertNotContains($newCard, $deck->getDeck());
    }

    public function testGetDeckSize()
    {
        $deck = new DeckOfCards();

        $this->assertIsInt($deck->getDeckSize());
        
        $deck->drawCard(10);

        $this->assertEquals(42, $deck->getDeckSize());
    }

    public function testGetDeck()
    {
        $deck = new DeckOfCards();

        $this->assertContainsOnlyInstancesOf("\App\Card\CardGraphic", $deck->getDeck());
    }

    public function testSetDeck()
    {
        $deck = new DeckOfCards();

        $cards = array(
            new CardGraphic("diamonds", 1),
            new CardGraphic("diamonds", 2)
        );

        $this->assertNotEqualsCanonicalizing($cards, $deck->getDeck());

        $deck->setDeck($cards);

        $this->assertEqualsCanonicalizing($cards, $deck->getDeck());
    }

    public function testShuffleDeck()
    {
        $deck = new DeckOfCards();

        $unOrderdDeck = $deck->getDeck();

        $this->assertEquals($deck->getDeck(), $unOrderdDeck);

        $deck->suffleDeck();

        $this->assertNotEquals($unOrderdDeck, $deck->getDeck());
    }

    public function testSortingOnNumbers()
    {
        $deck = new DeckOfCards();
        
        $newCards = array(
            new CardGraphic("diamonds", 3),
            new CardGraphic("diamonds", 2),
        );

        $deck->setDeck($newCards);
        $deck->sortCards();

        $cards = $deck->getDeck();

        $this->assertEquals($cards[0]->getValue(), 2);
        $this->assertEquals($cards[1]->getValue(), 3);
    }

    public function testSortingOnTypes()
    {
        $deck = new DeckOfCards();
        
        $newCards = array(
            new CardGraphic("hearts", 3),
            new CardGraphic("clubs", 5),
        );

        $deck->setDeck($newCards);
        $deck->sortCards();

        $cards = $deck->getDeck();

        $this->assertEquals($cards[0]->getValue(), 5);
        $this->assertEquals($cards[1]->getValue(), 3);
    }
}