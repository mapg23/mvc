<?php

namespace App\Card;

class Card
{
    public int $value;
    public string $type;

    public function __construct(string $type, int $value)
    {
        $this->value = $value;
        $this->type = $type;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getValue(): int
    {
        return $this->value;
    }
}
