<?php

namespace App\Card;

use App\Card\CardGraphic;

class CardHand
{
    private $hand = null;

    public function __construct()
    {
        $this->hand = [];
    }

    public function add(CardGraphic $card) {
        $this->hand[] = $card;
    }

}