<?php

namespace App\Project;

use App\Project\Hand;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Test cases for class Game.
 */
class GameTest extends TestCase
{
    /**
     * @SuppressWarnings("PMD")
     *
     * @param array <mixed> $cards.
     *
     * @return array <mixed>
    */
    private function createHand(
        array $cards,
        bool $taken = false,
        bool $stand = false,
        string $result = ""
    ): array {
        return [
            'cards' => $cards,
            'taken' => $taken,
            'stand' => $stand,
            'result' => $result
        ];
    }
    /**
     * @SuppressWarnings("PMD")
     *
     * @param array <mixed> $seat1.
     *
     * @param array <mixed> $seat2.
     *
     * @param array <mixed> $seat3.
     *
     * @param array <mixed> $dealer.
     *
     * @return array <mixed>
    */
    private function createData(
        array $seat1,
        array $seat2,
        array $seat3,
        array $dealer,
        bool $inProgress = false,
        bool $end = false
    ): array {
        return [
            'seat_1' => $seat1,
            'seat_2' => $seat2,
            'seat_3' => $seat3,
            'dealer' => $dealer,
            'inProgress' => $inProgress,
            'end' => $end
        ];
    }

    /**
     * Method that tests if the class is instantiated properly.
     *
     * @return void
     */
    public function testCreatedProperly(): void
    {
        $game = new Game($this->createData(
            $this->createHand(array(), true, false, ""),
            $this->createHand(array(), true, false, ""),
            $this->createHand(array(), true, false, ""),
            $this->createHand(array(), true, false, ""),
        ));

        $this->assertInstanceOf("\App\Project\Game", $game);
    }

    public function testSetResults(): void
    {
        // check if player hits 21
        $game = new Game($this->createData(
            $this->createHand(
                array(
                    new Card('diamonds', 10),
                    new Card('diamonds', 9),
                    new Card('diamonds', 2),
                ),
                true
            ),
            $this->createHand(array(
                new Card('hearts', 2),
                new Card('hearts', 3),
            ), true),
            $this->createHand(array(
                new Card('clubs', 10),
                new Card('clubs', 4)
            ), true),
            $this->createHand(
                array(
                    new Card('clubs', 10),
                    new Card('clubs', 4)
                ),
                true
            ),
        ));
        $game->setResults();
        $this->assertEquals("Win", $game->getSeats()['seat_1']['result']);
        $this->assertEquals("Lose", $game->getSeats()['seat_2']['result']);
        $this->assertEquals("Tie", $game->getSeats()['seat_3']['result']);

        // checks if player got lower than dealer but dealer is higher than 21
        $game = new Game($this->createData(
            $this->createHand(
                array(
                    new Card('diamonds', 9),
                    new Card('diamonds', 2),
                ),
                true
            ),
            $this->createHand(array(), false),
            $this->createHand(array(), false),
            $this->createHand(
                array(
                    new Card('clubs', 10),
                    new Card('clubs', 12),
                    new Card('clubs', 13)
                ),
                true
            ),
        ));
        $game->setResults();
        $this->assertEquals("Win", $game->getSeats()['seat_1']['result']);

        // check if player got more than 21
        $game = new Game($this->createData(
            $this->createHand(
                array(
                    new Card('diamonds', 10),
                    new Card('diamonds', 12),
                    new Card('diamonds', 13),
                ),
                true
            ),
            $this->createHand(array(), false),
            $this->createHand(array(), false),
            $this->createHand(
                array(
                    new Card('clubs', 10),
                    new Card('clubs', 12),
                    new Card('clubs', 13)
                ),
                true
            ),
        ));
        $game->setResults();
        $this->assertEquals("Lose", $game->getSeats()['seat_1']['result']);
    }

    public function testPlayRound(): void
    {
        // test all stand.
        $game = new Game($this->createData(
            $this->createHand(array(), true, false),
            $this->createHand(array(), true, true),
            $this->createHand(array(), true, true),
            $this->createHand(array(), true, true),//dealer
        ));

        $this->assertEquals(false, $game->end);
        $this->assertEquals(false, $game->inProgress);

        $game->stand(1);
        $game->playRound();

        $this->assertEquals(true, $game->end);

        // test if dealer stand when dealer score >= 17.
        $game = new Game($this->createData(
            $this->createHand(array(), true, false),
            $this->createHand(array(), true, false),
            $this->createHand(array(), true, false),
            $this->createHand(array(new Card('diamonds', 10), new Card('diamonds', 8)), true, false), // dealer
        ));

        $game->playRound();

        $this->assertEquals(true, $game->getDealer()['stand']);

        // tests if dealer adds a card if no other if conditions is true.

        $game = new Game($this->createData(
            $this->createHand(array(), true, false),
            $this->createHand(array(), true, false),
            $this->createHand(array(), true, false),
            $this->createHand(array(new Card('diamonds', 1), new Card('diamonds', 8)), true, false), // dealer
        ));

        $game->playRound();

        $this->assertEquals(3, count($game->getDealer()['cards']));
    }

    public function testTakeSeat(): void
    {
        $game = new Game($this->createData(
            $this->createHand(array(), false),
            $this->createHand(array(), false),
            $this->createHand(array(), false),
            $this->createHand(array(), true), // dealer
        ));

        $this->assertEquals(false, $game->getSeats()['seat_1']['taken']);

        $game->takeSeat(1);

        $this->assertEquals(true, $game->getSeats()['seat_1']['taken']);
    }

    public function testHit(): void
    {
        $game = new Game($this->createData(
            $this->createHand(array(), true),
            $this->createHand(array(), false),
            $this->createHand(array(), true, true),
            $this->createHand(array(), true), // dealer
        ));

        $game->hit(1);
        $game->hit(2);
        $game->hit(3);
        $this->assertEquals(1, count($game->getSeats()['seat_1']['cards']));
        $this->assertEquals(0, count($game->getSeats()['seat_2']['cards']));
        $this->assertEquals(0, count($game->getSeats()['seat_3']['cards']));
    }

    public function testGetSpecificSeat(): void
    {
        $game = new Game($this->createData(
            $this->createHand(array(new Card('diamonds', 2)), true, true),
            $this->createHand(array(), false),
            $this->createHand(array(), false),
            $this->createHand(array(), true), // dealer
        ));

        $seat = $game->getSpecificSeat(1);

        $this->assertEquals("seat_1", $seat['seat']);
        $this->assertEquals(true, $seat['taken']);
        $this->assertEquals(true, $seat['stand']);
    }

    public function testDataToTwig(): void
    {
        $game = new Game($this->createData(
            $this->createHand(array(new Card('diamonds', 2)), true),
            $this->createHand(array(), false),
            $this->createHand(array(), false),
            $this->createHand(array(), true), // dealer
        ));

        $twig = $game->dataToTwig();

        $this->assertEquals($game->inProgress, $twig['inProgress']);
        $this->assertEquals($game->end, $twig['end']);
    }

    public function testGetMatchStatus(): void
    {
        $game = new Game($this->createData(
            $this->createHand(array(), false),
            $this->createHand(array(), false),
            $this->createHand(array(), false),
            $this->createHand(array(), true), // dealer
        ));

        $status = $game->getMatchStatus();

        $this->assertEquals(false, $status['Round started']);
    }

}
