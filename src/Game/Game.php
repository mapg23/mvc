<?php

namespace App\Game;

use App\Card\CardGraphic;
use App\Card\CardHand;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Game
{
    private array $deck;
    /** @var array<string> */
    private array $types = ["spades", "hearts", "diamonds", "clubs"];

    private bool $endOfMatch = false;
    private string $result = "";

    private CardHand $computer;
    private CardHand $player;

    private SessionInterface $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;

        if ($this->session->has("game_deck")) 
        {
            $this->setDeck($this->session->get("game_deck"));
        } 
        else 
        {
            $this->generateDeck();
        }
        $this->player = new CardHand($this->session->get("p_hand")) ?? new CardHand();
        $this->computer = new CardHand($this->session->get("c_hand")) ?? new CardHand();
        
        if ($this->session->has("p_stand")) 
        {
            $this->loadStandsFromSession();
        }

        if ($this->session->has("result"))
        {
            $this->result = $this->session->get("result");
        }
        
        $this->saveToSession();
    }

    public function loadStandsFromSession()
    {
        $this->player->setStand($this->session->get("p_stand"));
        $this->computer->setStand($this->session->get("c_stand"));
    }

    public function saveToSession()
    {
        $this->session->set("p_hand", $this->player->getHand());
        $this->session->set("c_hand", $this->computer->getHand());
        $this->session->set("game_deck", $this->deck);

        $this->session->set("p_stand", $this->player->getStop());
        $this->session->set("c_stand", $this->computer->getStop());
        $this->session->set("end_of_match", $this->endOfMatch);
        $this->session->set("result", $this->result);
    }

    public function round(): void
    {
        $this->loadStandsFromSession();

        if ($this->player->getScore() > 21)
        {
            $this->player->setStand(true);
        }

        if ($this->computer->getScore() >= 17)
        {
            $this->computer->setStand(true);
        }

        if ($this->player->getStop()) 
        {
            $this->drawRecursive();

            $this->endOfMatch = true;
            
            $this->result = $this->displayResult();
            
            $this->saveToSession();
            
            return;
        }

        if ($this->computer->getStop())
        {
            $this->player->add($this->drawCard(1));
            $this->saveToSession();
            return;
        }

        $this->player->add($this->drawCard(1));
        $this->computer->add($this->drawCard(1));

        $this->saveToSession();
        return;
    }

    public function drawRecursive()
    {
        if ($this->computer->getScore() >= 17) {
            $this->computer->setStand(true);
            $this->saveToSession();
            return;
        }

        $this->computer->add($this->drawCard(1));
        $this->drawRecursive();
    }

    public function newMatch()
    {
        $this->player = new CardHand();
        $this->computer = new CardHand();
        $this->endOfMatch = false;
        $this->result = "";

        $this->saveToSession();
    }

    public function displayResult()
    {
        $playerScore = $this->player->getScore();
        $computerScore = $this->computer->getScore();

        if ($playerScore >= 22)
        {
            return "Lose";
        }

        if ($computerScore >= 22)
        {
            return "Win";
        }

        if ($playerScore > $computerScore)
        {
            return "Win";
        }

        if ($computerScore > $playerScore)
        {
            return "Lose";
        }

        return "Tie";

    }

    public function stand()
    {
        $this->player->setStand(true);
        $this->saveToSession();

        $this->round();
    }

    public function generateDeck() 
    {
        foreach($this->types as $type) {

            for ($i = 1; $i < 14; $i++) {
                $this->deck[] = new CardGraphic($type, $i);
            }
        }
    }

    public function getData() {
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

    /** @return array<CardGraphic> */
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

    public function setDeck(array $deck)
    {
        $this->deck = $deck;
    }

    public function getDeckSize()
    {
        return count($this->deck);
    }
}
