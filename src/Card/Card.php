<?php

namespace App\Card;

class Card
{
    public $value;
    public $type;

    public function __construct(string $type, int $value)
    {
        $this->value = $value;
        $this->type = $type;
    }

    public function get_type(): string
    {
        return $this->type;
    }

    public function get_value(): int
    {
        return $this->value;
    }
}
