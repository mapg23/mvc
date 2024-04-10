<?php

namespace App\Card;

use App\Card\CardGraphic;

class DeckOfCards
{
    /** @var array<CardGraphic> */
    protected array $deck = [];

    /** @var array<string> */
    private array $types = ["spades", "hearts", "diamonds", "clubs"];

    public function __construct()
    {
        foreach($this->types as $type) {

            for ($i = 1; $i < 14; $i++) {
                $this->deck[] = new CardGraphic($type, $i);
            }
        }
    }

    /** @return array<CardGraphic> */
    public function drawCard(int $ammount = 1): array
    {
        $randomKeys = array_rand($this->deck, $ammount);
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

    public function getDeckSize(): int
    {
        return count($this->deck);
    }

    /** @return array<CardGraphic> */
    public function getDeck(): array
    {
        return $this->deck;
    }

    /**
     * @param array<CardGraphic> $deck
     */
    public function setDeck(array $deck): void
    {
        $this->deck = $deck;
    }

    public function suffleDeck(): void
    {
        shuffle($this->deck);
    }

    public function sortCards(): void
    {
        /** @phpstan-ignore-next-line */
        usort($this->deck, array($this, "sorting"));
    }

    /** @return int|float */
    public static function sorting(CardGraphic $first, CardGraphic $second): int|float
    {
        $comparisson = strcmp($first->getType(), $second->getType());

        if ($comparisson != 0) {
            return $comparisson;
        }

        return $first->getValue() - $second->getValue();
    }
}
