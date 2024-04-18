<?php

namespace App\Game;

use App\Card\CardGraphic;
use App\Card\CardHand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Test cases for class Game.
 */
class GameTest extends TestCase
{
    private $session;

    /**
     * Method that invokes a Session mock.
     */
    protected function setUp(): void
    {
        $this->session = $this->createMock(SessionInterface::class);
    }
    
    /**
     * Method that tests if the class is instantiated properly.
     */
    public function testCreatedProperly()
    {   
        $game = new Game($this->session);        
        
        $this->assertInstanceOf("\App\Game\Game", $game);
    }

    /**
     * Method that tests Game::rounds().
     * This method will check if the correct result is produced.
     * 
     */
    public function testRounds()
    {
        $game = new Game($this->session);

        // player score > 21
        $game->player->setHand(array(
            new CardGraphic("diamonds", 10),
            new CardGraphic("diamonds", 12),
            new CardGraphic("diamonds", 13),
        ));

        $game->round();
        $this->assertTrue($game->player->getStop());
        $this->assertEquals("Lose", $game->result);

        // computer score > 17
        $game = new Game($this->session);

        $game->computer->setHand(array(
            new CardGraphic("diamonds", 10),
            new CardGraphic("diamonds", 8),
        ));

        $game->round();
        $this->assertTrue($game->computer->getStop());

        // computer has stopped.
        $game = new Game($this->session);

        $this->assertEquals([], $game->player->getHand());
        
        $game->computer->setStand(true);
        $game->round();

        $this->assertNotEquals([], $game->player->getHand());
    
        // no condition

        $game = new Game($this->session);

        $this->assertEquals([], $game->player->getHand());
        $this->assertEquals([], $game->computer->getHand());

        $game->round();
        
        $this->assertNotEquals([], $game->player->getHand());
        $this->assertNotEquals([], $game->computer->getHand());
    }


    /**
     * Test method for Game::newMatch().
     * This method will test if everything is reset when creating a new match.
     */
    public function testNewMatch()
    {
        $game = new Game($this->session);

        $game->player->setHand(array (
            new CardGraphic("diamonds", 2)
        ));
        
        $game->computer->setHand(array (
            new CardGraphic("diamonds", 2)
        ));
        $game->endOfMatch = true;
        $game->result = "old";

        $game->newMatch();

        $this->assertEquals([], $game->player->getHand());
        $this->assertEquals([], $game->computer->getHand());

        $this->assertFalse($game->endOfMatch);
        $this->assertEquals("", $game->result);
    }

    /**
     * Test method for Game::displayResult().
     * This method will test if the correct result is returned,
     * depending on a couple of conditions.
     */
    public function testDisplayResult()
    {
        $game = new Game($this->session);
        
        // player score >= 22
        $game->player->setHand(array(
            new CardGraphic("diamonds", 11),
            new CardGraphic("diamonds", 12),
            new CardGraphic("diamonds", 10),
        ));

        $this->assertEquals("Lose", $game->displayResult());

        // computer score >= 22
        $game->player->setHand([]);

        $game->computer->setHand(array(
            new CardGraphic("diamonds", 11),
            new CardGraphic("diamonds", 12),
            new CardGraphic("diamonds", 10),
        ));

        $this->assertEquals("Win", $game->displayResult());
        
        // player score > computer score
        $game->computer->setHand([]);
        $game->player->setHand(array(new CardGraphic("diamonds", 10)));
    
        $this->assertEquals("Win", $game->displayResult());

        // computer score > player score
        $game->player->setHand([]);
        $game->computer->setHand(array(new CardGraphic("diamonds", 10)));

        $this->assertEquals("Lose", $game->displayResult());

        // player score === computer score

        $game->player->setHand([]);
        $game->computer->setHand([]);

        $this->assertEquals("Tie", $game->displayResult());
    }

    /**
     * Test method for Game::stand().
     * This method will test if stand() sets player stop to true.
     */
    public function testStand()
    {
        $game = new Game($this->session);
        $game->stand();

        $this->assertTrue($game->player->getStop());
    }

    /**
     * Test method for Game::GenerateDeck.
     * This method will test if generating a deck, generates a different.
     */
    public function testGenerateDeck()
    {
        $game = new Game($this->session);
        $deck = $game->getDeck();

        $game->generateDeck();

        $this->assertNotEquals($deck, $game->getDeck());

        $this->assertContainsOnlyInstancesOf("\App\Card\CardGraphic", $game->getDeck());
    }

    /**
     * Test method for Game::GetData().
     * This method will test if GetData() returns and array with the right keys.
     */
    public function testGetData()
    {
        $game = new Game($this->session);


        $data = $game->getData();

        $this->assertArrayHasKey('p_hand', $data);
        $this->assertArrayHasKey('p_score', $data);
        $this->assertArrayHasKey('c_hand', $data);
        $this->assertArrayHasKey('c_score', $data);
        $this->assertArrayHasKey('end_of_match', $data);
        $this->assertArrayHasKey('res', $data);

    }
    /**
     * Test method for Game::DrawCard().
     * This method will test if drawing the last card from a deck will re-generate a new deck.
     * Allso check so that the drawn card is unset.
     */
    public function testDrawCard()
    {
        $game = new Game($this->session);
        $game->generateDeck();
        $drawnCard = $game->drawCard();
    
        $this->assertNotContains($drawnCard, $game->getDeck());
    
        $game->setDeck([]);
        $game->drawCard();

        $this->assertNotEmpty($game->getDeck());
    }
    /**
     * Test method for Game::setDeck().
     * This method will check that the new deck isnt the same as the previous one.
     */
    public function testSetDeck()
    {
        $game = new Game($this->session);
        $deck = $game->getDeck();

        $newDeck = array(
            new CardGraphic("diamonds", 2),
            new CardGraphic("diamonds", 3),
        );

        $game->setDeck($newDeck);

        $this->assertNotEquals($deck, $game->getDeck());
        $this->assertEqualsCanonicalizing($newDeck, $game->getDeck());
    }
    /**
     * Test method for Game::getDeckSize().
     * This method will make sure that the return type is of Int.
     * It will also check that its equal to sizeof() the deck.
     */
    public function testGetDeckSize()
    {
        $game = new Game($this->session);

        $this->assertIsInt($game->getDeckSize());
        $this->assertEquals(sizeof($game->getDeck()), $game->getDeckSize());
    }
}