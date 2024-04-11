<?php

namespace App\Card;

use App\Card\CardGraphic;

class CardHand
{
    /** @var array<CardGraphic>*/
    private array $hand;

    private bool $stop = false;

    public function __construct($existingHand = null)
    {  
        $this->hand = ($existingHand !== null) ? $existingHand : [];
    }

    public function add(array $cards): void
    {
        foreach($cards as $card) {
            $this->hand[] = $card;
        }
    }

    /** @return array<CardGraphic> */
    public function getHand(): array
    {
        return $this->hand;
    }

    public function hasStoped():bool
    {
        return $this->stop;
    }

    public function getScore(): int
    {
        return $this->calculateScore();
    }

    public function calculateScore(): int
    {
        $score = 0;

        for ($i = 0; $i < count($this->hand); $i++)
        {
            $value = $this->hand[$i]->getValue();

            if ($value > 10) {
                $score += 10;
                continue;
            }

            if ($value === 1) {
                $score += 11;
                continue;
            }

            $score += $value;
        }

        return $score;
    }

    public function setStand(bool $stop) {
        $this->stop = $stop;
    }

    public function getStop()
    {
        return $this->stop;
    }

}
