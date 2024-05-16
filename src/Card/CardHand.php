<?php

namespace App\Card;

use App\Card\CardGraphic;

class CardHand
{
    /** @var array<CardGraphic>*/
    private array $hand;

    private bool $stop = false;

    /** @param array<CardGraphic>|null $existingHand */
    public function __construct($existingHand = null)
    {
        $this->hand = ($existingHand !== null) ? $existingHand : [];
    }

    /** @param array<CardGraphic> $cards */
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

    /** @param array<CardGraphic> $hand */
    public function setHand(array $hand): void
    {
        $this->hand = $hand;
    }

    public function hasStoped(): bool
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
        $aceCount = 0;

        foreach($this->hand as $card) {
            $value = $card->getValue();

            if ($value === 1) {
                $aceCount++;
                continue;
            }

            $score += min($value, 10);
        }

        for ($i = 0; $i < $aceCount; $i++) {
            if ($score + 11 <= 21) {
                $score += 11;
            } else {
                $score += 1;
            }
        }
    
        return $score;
    }

    /** @param bool|null $stop */
    public function setStand(bool|null $stop): void
    {
        if (is_null($stop)) {
            return;
        }

        $this->stop = $stop;
    }

    public function getStop(): bool
    {
        return $this->stop;
    }

}
