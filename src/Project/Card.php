<?php

namespace App\Project;

class Card
{
    private string $type;
    private int $number;

    public function __construct(string $type, int $number)
    {
        $this->type = $type;
        $this->number = $number;
    }

    public function getImage(): string
    {
        return $this->number . "_of_" . $this->type . ".png";
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getNumber(): int
    {
        return $this->number;
    }
}
