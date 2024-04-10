<?php

namespace App\Card;

use App\Card\CardGraphic;

class CardHand
{
    /** @var array<CardGraphic>*/
    private array $hand;

    public function __construct()
    {
        $this->hand = [];
    }

    public function add(CardGraphic $card): void
    {
        $this->hand[] = $card;
    }

    /** @return array<CardGraphic> */
    public function getHand(): array
    {
        return $this->hand;
    }

}
