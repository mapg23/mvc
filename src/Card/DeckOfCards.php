<?php

namespace App\Card;

use App\Card\CardGraphic;

class DeckOfCards
{
    protected $deck = [];
    private $types = ["spades", "hearts", "diamonds", "clubs"];

    public function __construct()
    {
        foreach($this->types as $type) {

            for ($i = 1; $i < 14; $i++) {
                $this->deck[] = new CardGraphic($type, $i);
            }
        }
    }

    public function draw_card(int $ammount = 1): array
    {
        $random_keys = array_rand($this->deck, $ammount);
        $cards = [];

        if (gettype($random_keys) === "integer") {
            $random_keys = [$random_keys];
        }

        foreach($random_keys as $key) {
            $cards[] = $this->deck[$key];
            unset($this->deck[$key]);
        }

        return $cards;
    }

    public function get_deck_size(): int
    {
        return count($this->deck);
    }

    public function get_deck(): array
    {
        return $this->deck;
    }

    public function set_deck(array $deck): Void
    {
        $this->deck = $deck;
    }

    public function suffle_deck(): Void
    {
        shuffle($this->deck);
    }

    public function sort_cards()
    {
        usort($this->deck, array($this, 'sorting'));
    }

    public static function sorting($a, $b) {
        $comparisson = strcmp($a->get_type(), $b->get_type());

        if ($comparisson != 0) {
            return $comparisson;
        }

        return $a->get_value() - $b->get_value();
    }
}
