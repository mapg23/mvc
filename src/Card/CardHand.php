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
        $count = sizeof($this->hand);

        for ($i = 0; $i < $count; $i++) {
            $value = $this->hand[$i]->getValue();

            if ($value === 1) {
                $aceCount++;
                continue;
            }

            if($value > 10) {
                $score += 10;
                continue;
            }

            $score += $value;
        }

        for ($i = 0; $i < $aceCount; $i++) {
            if($score + 11 > 21) {
                $score += 1;
                continue;
            }

            /** @phpstan-ignore-next-line */
            if($score + 11 < 22) {
                $score += 11;
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
