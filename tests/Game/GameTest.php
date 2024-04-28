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
    /**
     * Method used to to create data that represents session data.
     * @param array<mixed> $deck
     * @param array<mixed> $pHand
     * @param array<mixed> $cHand
     * @return array<mixed>
     */
    private function createData(
        array $deck = null,
        array $pHand = null,
        array $cHand = null,
        bool $pStand = null,
        bool $cStand = null,
        string $res = null
    ): array {
        return [
            'deck' => $deck,
            'p_hand' => $pHand,
            'c_hand' => $cHand,
            'p_stand' => $pStand,
            'c_stand' => $cStand,
            'res' => $res,
        ];
    }

    /**
     * Method that tests if the class is instantiated properly.
     *
     * @return void
     */
    public function testCreatedProperly(): void
    {
        $game = new Game($this->createData());

        $this->assertInstanceOf("\App\Game\Game", $game);
    }

    /**
     * Method that tests Game::rounds().
     * This method will check if the correct result is produced.
     *
     * @return void
     */
    public function testRounds(): void
    {
        $hand = array(
            new CardGraphic("diamonds", 10),
            new CardGraphic("diamonds", 12),
            new CardGraphic("diamonds", 13),
        );

        $game = new Game($this->createData(null, $hand));

        // player score > 21

        $game->round();
        $this->assertTrue($game->player->getStop());
        $this->assertEquals("Lose", $game->result);

        // computer score > 17

        $hand = array(
            new CardGraphic("diamonds", 10),
            new CardGraphic("diamonds", 8),
        );

        $game = new Game($this->createData(null, null, $hand));

        $game->round();
        $this->assertTrue($game->computer->getStop());

        // computer has stopped.
        $game = new Game($this->createData());

        $this->assertEquals([], $game->player->getHand());

        $game->computer->setStand(true);
        $game->round();

        $this->assertNotEquals([], $game->player->getHand());

        // no condition
        $game = new Game($this->createData());

        $this->assertEquals([], $game->player->getHand());
        $this->assertEquals([], $game->computer->getHand());

        $game->round();

        $this->assertNotEquals([], $game->player->getHand());
        $this->assertNotEquals([], $game->computer->getHand());
    }

    /**
     * Test method for Game::newMatch().
     * This method will test if everything is reset when creating a new match.
     *
     * @return void
     */
    public function testNewMatch(): void
    {
        $playerHand = array(
            new CardGraphic("diamonds", 2)
        );

        $computerHand = array(
            new CardGraphic("diamonds", 2)
        );

        $game = new Game($this->createData(null, $playerHand, $computerHand));

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
     *
     * @return void
     */
    public function testDisplayResult(): void
    {
        // player score >= 22
        $hand = array(
            new CardGraphic("diamonds", 11),
            new CardGraphic("diamonds", 12),
            new CardGraphic("diamonds", 10),
        );

        $game = new Game($this->createData(null, $hand));

        $this->assertEquals("Lose", $game->displayResult());

        // computer score >= 22

        $hand = array(
            new CardGraphic("diamonds", 11),
            new CardGraphic("diamonds", 12),
            new CardGraphic("diamonds", 10),
        );

        $game = new Game($this->createData(null, null, $hand));

        $this->assertEquals("Win", $game->displayResult());

        // player score > computer score
        $hand = array(
            new CardGraphic("diamonds", 10)
        );

        $game = new Game($this->createData(null, $hand));

        $this->assertEquals("Win", $game->displayResult());

        // computer score > player score
        $hand = array(
            new CardGraphic("diamonds", 10)
        );

        $game = new Game($this->createData(null, null, $hand));

        $this->assertEquals("Lose", $game->displayResult());

        // player score === computer score
        $game = new Game($this->createData());

        $this->assertEquals("Tie", $game->displayResult());
    }

    /**
     * Test method for Game::stand().
     * This method will test if stand() sets player stop to true.
     *
     * @return void
     */
    public function testStand(): void
    {
        $game = new Game($this->createData());
        $game->stand();

        $this->assertTrue($game->player->getStop());
    }

    /**
     * Test method for Game::GenerateDeck.
     * This method will test if generating a deck, generates a different.
     *
     * @return void
     */
    public function testGenerateDeck(): void
    {
        $game = new Game($this->createData());
        $deck = $game->getDeck();

        $game->generateDeck();

        $this->assertNotEquals($deck, $game->getDeck());

        $this->assertContainsOnlyInstancesOf("\App\Card\CardGraphic", $game->getDeck());
    }

    /**
     * Test method for Game::GetData().
     * This method will test if GetData() returns and array with the right keys.
     *
     * @return void
     */
    public function testGetData(): void
    {
        $game = new Game($this->createData());


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
     *
     * @return void
     */
    public function testDrawCard(): void
    {
        $game = new Game($this->createData());
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
     *
     * @return void
     */
    public function testSetDeck(): void
    {
        $game = new Game($this->createData());

        $this->assertNotEquals([], $game->getDeck());

        $deck = $game->getDeck();

        $newDeck = array(
            new CardGraphic("diamonds", 2),
            new CardGraphic("diamonds", 3),
        );

        $game->setDeck($newDeck);

        $this->assertNotEquals($deck, $game->getDeck());
        $this->assertEqualsCanonicalizing($newDeck, $game->getDeck());

        $game->setDeck(null);

        $this->assertNotEquals([], $game->getDeck());

        $this->assertIsInt($game->getDeckSize());
        $this->assertEquals(sizeof($game->getDeck()), $game->getDeckSize());
    }

    public function testSaveData(): void
    {
        $game = new Game($this->createData());
        $data = $game->saveData();


        $this->assertArrayHasKey('deck', $data);
        $this->assertArrayHasKey('p_hand', $data);
        $this->assertArrayHasKey('c_hand', $data);
        $this->assertArrayHasKey('p_stand', $data);
        $this->assertArrayHasKey('c_stand', $data);
        $this->assertArrayHasKey('res', $data);
    }
}
