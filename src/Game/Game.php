<?php

namespace App\Game;

use App\Card\CardGraphic;
use App\Card\CardHand;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

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

    private SessionInterface $session;

    /**
     * Method that executes on start.
     * @param SessionInterface $session, this is the session that store all data.
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;

        $this->setDeck($this->session->get("game_deck"));

        $this->player = new CardHand($this->session->get("p_hand"));
        $this->computer = new CardHand($this->session->get("c_hand"));

        if ($this->session->has("p_stand")) {
            $this->loadStandsFromSession();
        }

        if ($this->session->has("result")) {
            $this->result = $this->session->get("result");
        }

        $this->saveToSession();
    }

    /**
     * This method loads whos turn it is to draw a card from session.
     * 
     * @return void
     */
    public function loadStandsFromSession(): void
    {
        $this->player->setStand($this->session->get("p_stand"));
        $this->computer->setStand($this->session->get("c_stand"));
    }

    /**
     * Method that saves all variables to session.
     * 
     * @return void
     */
    public function saveToSession(): void
    {
        $this->session->set("p_hand", $this->player->getHand());
        $this->session->set("c_hand", $this->computer->getHand());
        $this->session->set("game_deck", $this->deck);

        $this->session->set("p_stand", $this->player->getStop());
        $this->session->set("c_stand", $this->computer->getStop());
        $this->session->set("end_of_match", $this->endOfMatch);
        $this->session->set("result", $this->result);
    }

    /**
     * This method is used to represent a round of the game.
     * 
     * @return void
     */
    public function round(): void
    {
        $this->loadStandsFromSession();

        if ($this->player->getScore() > 21) {
            $this->player->setStand(true);
            $this->result = $this->displayResult();
            $this->saveToSession();
            return;
        }

        if ($this->computer->getScore() >= 17) {
            $this->computer->setStand(true);
        }

        if ($this->player->getStop()) {
            $this->drawRecursive();
            $this->result = $this->displayResult();
            $this->saveToSession();
            return;
        }

        if ($this->computer->getStop()) {
            $this->player->add($this->drawCard(1));
            $this->saveToSession();
            return;
        }

        $this->player->add($this->drawCard(1));
        $this->computer->add($this->drawCard(1));

        $this->saveToSession();
        return;
    }

    /**
     * Method used for computer to draw multiple cards while the player has stoped.
     * 
     * @return void
     */
    public function drawRecursive(): void
    {
        if ($this->computer->getScore() >= 17) {
            $this->computer->setStand(true);
            $this->saveToSession();
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

        $this->saveToSession();
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
     * Method used to make the playet stand.
     * 
     * @return void
     */
    public function stand(): void
    {
        $this->player->setStand(true);
        $this->saveToSession();

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
     * Method used to return all data from the game.
     * The array is used for display in the twig files.
     *
     * @return array<string, array<CardGraphic> |bool|int|string>
     */
    public function getData(): array
    {
        $data = [
            "p_hand" => $this->player->getHand(),
            "p_score" => $this->player->getScore(),

            "c_hand" => $this->computer->getHand(),
            "c_score" => $this->computer->getScore(),

            "end_of_match" => $this->endOfMatch,
            "res" => $this->result
        ];

        return $data;
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
