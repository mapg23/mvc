<?php

namespace App\Card;

class CardGraphic extends Card
{
    private $representation = [
        "spades" => [
            '🂡','🂢','🂣','🂤',
            '🂥','🂦','🂧','🂨',
            '🂩','🂪','🂫','🂭','🂮'
        ],
        "hearts" => [
            '🂱','🂲','🂳','🂴',
            '🂵','🂶','🂷','🂸',
            '🂹','🂺','🂻','🂽','🂾'
        ],
        "diamonds" => [
            '🃁','🃂','🃃','🃄',
            '🃅','🃆','🃇','🃈',
            '🃉','🃊','🃋','🃍','🃎'
        ],
        "clubs" => [
            '🃑','🃒','🃓','🃔',
            '🃕','🃖','🃗','🃘',
            '🃙','🃚','🃛','🃝','🃞'
        ]
    ];

    public function __construct(string $type, int $value)
    {
        parent::__construct($type, $value);
    }

    public function getAsString(): string
    {
        return $this->representation[$this->type][$this->value - 1];
    }
}