<?php

namespace App\Project;

class Card 
{
    private bool $visibleCard = false;

    private string $defaultCard = "cards/default.jpeg";
    private string $cardPath;

    private int|null $number;
    private string|null $type;

    public function __construct(int $number = null, string $type = null)
    {
        $this->number = $number;
        $this->type = $type;
    }

    public function getImage()
    {
        if (!$this->visibleCard) {
            return $this->defaultCard;
        }

        $this->cardPath = "cards/" . $this->number . "_of_" . $this->type . ".png";

        return $this->cardPath;
    }

    public function setVisibility(bool $argument)
    {
        $this->visibleCard = $argument;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType(string $type)
    {
        $this->type = $type;
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function setNumber(int $number)
    {
        $this->number = $number;
    }
}