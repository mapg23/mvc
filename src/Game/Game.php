<?php

namespace App\Game;

use App\Card\CardGraphic;
use App\Card\DeckOfCards;

class Game extends DeckOfCards
{
    protected array $deck;
    
    private bool $playersTurn;

    private array $players = [];

    public function __construct()
    {
        parent::__construct();
        $playersTurn = false;
    }

    public function drawCard(int $ammount = 1): array
    {
        $card = parent::drawCard($ammount);


        return $card;
    }
    
}
