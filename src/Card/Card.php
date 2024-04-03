<?php

namespace App\Card;

class Card
{
    protected $value;
    protected $type;

    public function __construct(string $type, int $value)
    {
        $this->value = $value;
        $this->type = $type;
    }
}