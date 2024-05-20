<?php

namespace App\Project;
use App\Project\Rules;


class Hand
{
    private array $hand;
    private int $money;
    private int $bet;
    private bool $fold;
    private bool $check;
    private bool $raise;

    public function __construct(array $data)
    {
        $this->hand = $data['hand'] ?? [];
        $this->fold = $data['fold'] ?? false;
        $this->money = $data['money'] ?? 1000;
        $this->bet = $data['bet'] ?? 0; 
    }

    public function getHand()
    {
        return $this->hand;
    }

    public function makeAllVisible()
    {
        foreach ($this->hand as $card)
        {
            $card->setVisibility(true);
        }
    }

    public function setFold(bool $fold)
    {
        $this->fold = $fold;
    }

    public function getFold()
    {
        return $this->fold;
    }

    public function getMoney()
    {
        return $this->money;
    }

    public function setMoney(int $money)
    {
        $this->money = $money;
    }

    public function setBet(int $bet)
    {
        $this->bet = $bet;
    }

    public function getBet()
    {
        return $this->bet;
    }

    public function saveHand(): array
    {
        return [
            'hand' => $this->getHand(),
            'fold' => $this->getFold(),
            'money' => $this->getMoney(),
            'bet' => $this->getBet()
        ];
    }
}