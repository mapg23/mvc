<?php

namespace App\Card;

use App\Card\CardGraphic;

class CardHand
{
    private $hand = [];

    public function add(CardGraphic $card) {
        $this->hand[] = $card;
    }

}