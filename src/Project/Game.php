<?php

namespace App\Project;

use App\Project\Card;
use App\Project\Hand;
use App\Project\Rules;

class Game
{
    /** @var array<Card> */
    private array $deck;
    /** @var array<Card> */
    private array $dealerDeck;
    private int $pot;
    private int $round;

    private Hand $player;
    private Hand $computer1;
    private Hand $computer2;

    private string|null $res;

    public function __construct(
        array $data,
    ) {
        $testDeck = [
            new Card(3, "hearts"),
            new Card(3, "diamonds"),
            new Card(3, "clubs"),
            new Card(5, "diamonds"),
            new Card(5, "hearts")
        ];
        $this->deck = $data['deck'] ?? $this->generateDeck();
        $this->dealerDeck = $data['dealer'] ?? $testDeck;
        // $this->dealerDeck = $testDeck;
        $this->pot = $data['pot'] ?? 0;
        $this->round = $data['round'] ?? 0;

        $data['player1']['hand'] = $data['player1']['hand'] ?? $this->deal(2);
        $data['player2']['hand'] = $data['player2']['hand'] ?? $this->deal(2);
        $data['player3']['hand'] = $data['player3']['hand'] ?? $this->deal(2);

        $this->computer1 = new Hand($data['player1']);
        $this->player = new Hand($data['player2']);
        $this->computer2 = new Hand($data['player3']);

        $rules = new Rules($this->player->getHand(), $this->getDealerDeck());

        $this->res = $rules->valuateCards();

        if ($this->round >= 1 AND $this->round <= 5)
        {
            $this->dealerDeck[$this->round - 1]->setVisibility(true);
        }


        $this->player->makeAllVisible();
    }

    public function generateDeck(): array
    {
        $types = ['hearts', 'diamonds', 'clubs', 'spades'];
        $numbers = [2,3,4,5,6,7,8,9,10,11,12,13,14];
        $newDeck = [];

        foreach ($types as $type) {
            foreach ($numbers as $number) {
                $newDeck[] = new Card($number, $type);
            }
        }

        shuffle($newDeck);

        return $newDeck;
    }

    public function shuffleDeck(): void
    {
        shuffle($this->deck);
    }

    public function deal(int $amount): array
    {
        return array_splice($this->deck, 0, $amount);
    }

    public function getDeck(): array
    {
        return $this->deck;
    }

    public function getDealerDeck(): array
    {
        return $this->dealerDeck;
    }

    public function getPot(): int
    {
        return $this->pot;
    }

    public function getRound(): int
    {
        return $this->round;
    }

    public function playRound()
    {

    }

    public function getDataForTwig(): array
    {
        return [
            'dealer' => $this->getDealerDeck(),
            'pot' => $this->getPot(),
            'res' => $this->res,

            'player1' => $this->computer1->saveHand(),
            'player2' => $this->player->saveHand(),
            'player3' => $this->computer2->saveHand()
        ];
    }

    public function saveDataToSession(): array
    {
        return [
            'deck' => $this->getDeck(),
            'dealer' => $this->getDealerDeck(),
            'pot' => $this->getPot(),
            'round' => $this->getRound(),

            'player1' => $this->computer1->saveHand(),
            'player2' => $this->player->saveHand(),
            'player3' => $this->computer2->saveHand()
        ];
    }
}