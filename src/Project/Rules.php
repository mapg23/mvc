<?php

namespace App\Project;

class Rules
{
    private array $hand;
    private array $dealer;
    private array $merged;

    public function __construct(array $hand, array $dealer)
    {
        $this->hand = $hand;
        $this->dealer = $dealer;

        $this->merged = array_merge($this->hand, $this->dealer);
        rsort($this->merged);

    }

    public function cardCounts()
    {
        $counts = [];

        foreach($this->merged as $card) {

            if (isset($counts[$card->getNumber()])) {
                $counts[$card->getNumber()]++;
                continue;
            }

            $counts[$card->getNumber()] = 1;
        }

        return $counts;
    }

    public function valuateCards()
    {
        if ($this->straightFlush()) {
            return $this->straightFlush();
        }

        if ($this->fourOfAKind()) {
            return $this->fourOfAKind();
        }

        if ($this->fullHouse()) {
            return $this->fullHouse();
        }

        if ($this->flush()) {
            return $this->flush();
        }

        if ($this->straight()) {
            return $this->straight();
        }

        if ($this->threeOfAKind()) {
            return $this->threeOfAKind();
        }

        if ($this->twoPairs()) {
            return $this->twoPairs();
        }

        if ($this->pairs()) {
            return $this->pairs();
        }

        return $this->highCards();
    }

    public function getMerged()
    {
        return $this->merged;
    }

    public function straightFlush()
    {
        if ($this->straight() AND $this->flush()) {
            return "Straight Flush";
        }
    }

    public function fourOfAKind()
    {
        $counts = $this->cardCounts();
        $pairs = [];

        foreach($counts as $card => $count) {
            if ($count == 4) {
                $pairs[] = $card;
            }
        }

        if (!empty($pairs)) {
            return "Four of a kind: " . $pairs[0];
        }
    }

    public function fullHouse()
    {
        if ($this->threeOfAKind() AND $this->pairs()) {
            return "Full house";
        }
    }

    public function flush()
    {
        $colors = [];
        foreach ($this->merged as $card) {
            if (isset($colors[$card->getType()])) {
                $colors[$card->gettype()]++;
                continue;
            }

            $colors[$card->getType()] = 1;
        }

        foreach($colors as $color) {
            if ($color >= 5) {
                return "Flush";
            }
        }
    }

    public function straight()
    {
        $sortedCards = [];
        
        foreach ($this->merged as $card) {
            $sortedCards[] = $card->getNumber(); 
        }
        
        $unique = array_unique($sortedCards);
        sort($unique);

        $straight = true;
        $count = 0;
        for($i = 0; $i < count($unique) - 1; $i++) {
            $count++;

            if ($count >= 5) {
                break;
            }

            if ($unique[$i] + 1 !== $unique[$i + 1]) {
                $straight = false;
            }
        }

        if ($straight) {
            return "Straight";
        }
    }

    public function threeOfAKind()
    {
        $counts = $this->cardCounts();
        $pairs = [];

        foreach($counts as $card => $count) {
            if ($count == 3) {
                $pairs[] = $card;
            }
        }

        if (!empty($pairs)) {
            return "Three of a kind: " . $pairs[0];
        }
    }

    public function twoPairs()
    {
        $counts = $this->cardCounts();
        $pairs = [];

        foreach($counts as $card => $count) {
            if ($count == 2) {
                $pairs[] = $card;
            }
        }

        if (count($pairs) >= 2) {
            return "Two pair of: " . $pairs[0] . " And " . $pairs[1];
        }
    }

    public function pairs()
    {
        $counts = $this->cardCounts();
        $pairs = [];
        foreach ($this->merged as $card) {

            if (isset($counts[$card->getNumber()])) {
                $counts[$card->getNumber()]++;
                continue;
            }
            
            $counts[$card->getNumber()] = 1;
        }

        foreach($counts as $card => $count) {
            if ($count == 2) {
                $pairs[] = $card;
            }
        }

        if (isset($pairs[0])) {
            return "Pairs of " . $pairs[0];
        }
    }

    public function highCards()
    {
        $sum = 0;

        foreach ($this->hand as $card) {
            $sum += $card->getNumber();
        }

        return "HighCard: " . $sum;
    }
}