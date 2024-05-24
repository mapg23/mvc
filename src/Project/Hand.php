<?php

namespace App\Project;

class Hand
{
    private bool $taken = false;
    private array $cards;
    private int $index;
    private bool $stand;
    private string $result;

    public function __construct(
        int $index,
        bool $taken,
        array $cards,
        bool $stand,
        string $result,

    ){
        $this->index = $index;
        $this->taken = $taken;
        $this->cards = $cards;
        $this->stand = $stand;
        $this->result = $result;
    }

    public function add(array $card)
    {
        $this->cards[] = $card[0];
    }

    public function getScore()
    {
        $aceCount = 0;
        $count = 0;

        foreach($this->cards as $card) {
            if ($card->getNumber() === 14) {
                $aceCount++;
                continue;
            }
            $count += min($card->getNumber(), 10);
        }

        for ($i = 0; $i < $aceCount; $i++) {
            if ($count + 11 <= 21) {
                $count += 11;
                continue;
            }
            $count += 1;
        }
        return $count;
    }

    public function getIndex()
    {
        return $this->index;
    }

    public function getCards()
    {
        return $this->cards;
    }

    public function getTaken()
    {
        return $this->taken;
    }

    public function setTaken(bool $param)
    {
        $this->taken = $param;
    }

    public function setStand(bool $param)
    {
        $this->stand = $param;
    }

    public function getStand()
    {
        return $this->stand;
    }

    public function setResult(string $param)
    {
        $this->result = $param;
    }

    public function getResult()
    {
        return $this->result;
    }

    public function saveData()
    {
        return [
            'cards' => $this->getCards(),
            'taken' => $this->getTaken(),
            'stand' => $this->getStand(),
            'result' => $this->getResult()
        ];
    }
}