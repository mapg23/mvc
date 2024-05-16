<?php

namespace App\Game;

use App\Card\CardGraphic;
use App\Card\CardHand;

/**
 * Class that represents the game itself.
 */
class Game
{
    /** @var array<CardGraphic> */
    private array $deck;
    /** @var array<string> */
    private array $types = ["spades", "hearts", "diamonds", "clubs"];

    public bool $endOfMatch = false;
    public string $result = "";

    public CardHand $computer;
    public CardHand $player;

    /**
     * Method that executes on start.
     * @param array<mixed> $data
     */
    public function __construct(array $data)
    {
        $this->generateDeck();

        if (!is_null($data['deck'])) {
            $this->setDeck($data['deck']);
        }

        $this->player = new CardHand($data['p_hand']);
        $this->computer = new CardHand($data['c_hand']);

        $this->computer->setStand(($data['c_stand'] !== null) ? $data['c_stand'] : false);
        $this->player->setStand(($data['p_stand'] !== null) ? $data['p_stand'] : false);

        $this->result = $data['res'] !== null ? $data['res'] : "";
    }

    /**
     * This method is used to represent a round of the game.
     *
     * @return void
     */
    public function round(): void
    {

        if ($this->player->getScore() > 21) {
            $this->player->setStand(true);
            $this->result = $this->displayResult();
            return;
        }

        if ($this->computer->getScore() >= 17) {
            $this->computer->setStand(true);
        }

        if ($this->player->getStop()) {
            $this->drawRecursive();
            $this->result = $this->displayResult();
            return;
        }

        if ($this->computer->getStop()) {
            $this->player->add($this->drawCard(1));
            return;
        }

        $this->player->add($this->drawCard(1));
        $this->computer->add($this->drawCard(1));

        return;
    }

    /**
     * Method used for computer to draw multiple cards while the player has stopped.
     *
     * @return void
     */
    public function drawRecursive(): void
    {
        if ($this->computer->getScore() >= 17) {
            $this->computer->setStand(true);
            return;
        }

        $this->computer->add($this->drawCard(1));
        $this->drawRecursive();
    }

    /**
     * Method that resets all variables in order to start a new match.
     *
     * @return void
     */
    public function newMatch(): void
    {
        $this->player = new CardHand();
        $this->computer = new CardHand();
        $this->endOfMatch = false;
        $this->result = "";
    }

    /**
     * Method that returns Lose, Win or Tie depending on the outcome of the match.
     *
     * @return string
     */
    public function displayResult(): string
    {
        $playerScore = $this->player->getScore();
        $computerScore = $this->computer->getScore();
        $this->endOfMatch = true;

        if ($playerScore >= 22) {
            return "Lose";
        }

        if ($computerScore >= 22) {
            return "Win";
        }

        if ($playerScore > $computerScore) {
            return "Win";
        }

        if ($computerScore > $playerScore) {
            return "Lose";
        }

        return "Tie";
    }

    /**
     * Method used to make the player stand.
     *
     * @return void
     */
    public function stand(): void
    {
        $this->player->setStand(true);

        $this->round();
    }

    /**
     * Method used to generate a new deck.
     *
     * @return void
     */
    public function generateDeck(): void
    {
        foreach($this->types as $type) {

            for ($i = 1; $i < 14; $i++) {
                $this->deck[] = new CardGraphic($type, $i);
            }
        }
    }

    /**
     * Method used to store data to session.
     * @return array <string, array<CardGraphic> |bool|int|string>
     */
    public function saveData(): array
    {
        return [
            "deck" => $this->getDeck(),
            "p_hand" => $this->player->getHand(),
            "c_hand" => $this->computer->getHand(),
            "p_stand" => $this->player->getStop(),
            "c_stand" => $this->computer->getStop(),
            "res" => $this->result
        ];
    }

    /**
     * Method used to display data to twig files.
     * @return array <string, array<CardGraphic> |bool|int|string>
     */
    public function getData(): array
    {
        return [
            "deck" => $this->getDeck(),
            "p_hand" => $this->player->getHand(),
            "p_score" => $this->player->getScore(),
            "c_hand" => $this->computer->getHand(),
            "c_score" => $this->computer->getScore(),
            "end_of_match" => $this->endOfMatch,
            "res" => $this->result
        ];
    }

    /**
     * Method used to draw a card from the deck.
     * The returned card will be added to the player or computer hand.
     *
     * @return array<CardGraphic>
     */
    public function drawCard(int $amount = 1): array
    {
        if ($this->getDeckSize() === 0) {
            $this->generateDeck();
        }

        $randomKeys = array_rand($this->deck, $amount);
        $cards = [];

        if (gettype($randomKeys) === "integer" || gettype($randomKeys) === "string") {
            $randomKeys = [$randomKeys];
        }

        foreach($randomKeys as $key) {
            $cards[] = $this->deck[$key];
            unset($this->deck[$key]);
        }

        return $cards;
    }

    /**
     * Method used to set the deck.
     * Used when taking a deck from session.
     * @param array<CardGraphic> $deck
     */
    public function setDeck(array $deck = null): void
    {
        if (is_null($deck)) {
            $this->generateDeck();
            return;
        }

        $this->deck = $deck;
        return;
    }

    /**
     * Method used to get the deck.
     * @return array<CardGraphic>
     */
    public function getDeck(): array
    {
        return $this->deck;
    }

    /**
     * Method used to get the size of the deck.
     *
     * @return int
     */
    public function getDeckSize(): int
    {
        return count($this->deck);
    }
}
