<?php

namespace App\Project;

class Rules
{
    private array $hand;
    private array $originalHand;
    private int $round;

    private string $result;
    private int $score = 0;
    private array $ranks = [
        "highCards" => 1,
        "pairs" => 2,
        "twoPairs" => 3,
        "threeOfAKind" => 4,
        "straight" => 5,
        "flush" => 6,
        "fullHouse" => 7,
        "fourOfAKind" => 8,
        "straightFlush" => 9
    ];

    public function __construct(array $hand, array $dealer, int $round)
    {
        $this->originalHand = $hand;
        $this->hand = $hand;

        $this->round = $round;

        if ($this->round >= 1) {
            for ($i = 0; $i < $this->round; $i++) {
                $this->hand[] = $dealer[$i];
            }
        }
    }
    public function getHand() {
        return $this->hand;
    }

    public function getRank()
    {
        return $this->calculateRank();
    }

    public function getRankScore()
    {
        return $this->ranks[$this->calculateRank()];
    }

    public function calculateRank()
    {
        foreach(array_reverse($this->ranks) as $key => $value)
        {
            $func = $key;
            if($this->$func() == $key) {
                return $this->$func();
            }
        }
    }
    
    public function checkIfIsLadderDESC(array $array)
    {
        arsort($array);
        $reindexedArray = array_values($array);

        $count = 0;
        for ($i = 1; $i < count($reindexedArray); $i++) {
            $count++;

            if ($count >= 5) {
                return true;
            }

            if (intval($reindexedArray[$i]) != intval($reindexedArray[$i - 1] - 1) ) {
                return false;
            }
        }
        return true;
    }

    public function checkIfIsLadder(array $array)
    {
        asort($array);
        $reindexedArray = array_values($array);

        $count = 0;
        for ($i = 1; $i < count($reindexedArray); $i++) {
            $count++;

            if ($count >= 5) {
                return true;
            }

            if (intval($reindexedArray[$i]) != intval($reindexedArray[$i - 1] + 1) ) {
                return false;
            }
        }
        return true;
    }

    public function straightFlush()
    {
        $colorCount = [];
        foreach($this->hand as $card) {
            if (isset($colorCount[$card->getType()])) {
                $colorCount[$card->gettype()]++;
                continue;
            }
            $colorCount[$card->gettype()] = 1;
        }

        $key = array_search(max($colorCount), $colorCount);
        $filteredArray = [];

        foreach($this->hand as $card) {
            if ($card->getType() === $key) {
                $filteredArray[] = $card->getNumber();
            }
        }

        $unique_array = array_unique($filteredArray);
        asort($unique_array);

        if ($this->checkIfIsLadder($unique_array) && count($unique_array) == 5) {
            return "straightFlush";
        }
        return "nothing";
    }

    public function fourOfAKind()
    {
        $numbArray = [];

        foreach($this->hand as $card) {
            $numbArray[] = $card->getNumber();
        }

        $vals = array_count_values($numbArray);

        if (max(array_values($vals)) != "4") {
            return "nothing";
        }

        arsort($vals);
        $key = array_key_first($vals);

        if (intval($vals[$key]) == 4) {
            $this->score = intval($vals[$key]) * 4;
            return "fourOfAKind";
        }

        return "nothing";
    }

    public function fullHouse()
    {
        $numbArray = [];
        $count = [];

        foreach($this->hand as $card) {
            $numbArray[] = $card->getNumber();
        }

        $vals = array_count_values($numbArray);
        arsort($vals);

        $first = key($vals);
        next($vals);
        $second = key($vals);

        if ($vals[$first] != "3" && $vals[$second] != "2") {
        }
        
        if (intval($vals[$first]) == 3 && intval($vals[$second]) == 2) {
            $this->score = (intval($first) * intval($vals[$first]) + intval($second) * intval($vals[$second]));
            return "fullHouse";
        }
        
        return "nothing";
    }

    public function flush()
    {
        $colorCount = [];

        foreach($this->hand as $card) {
            $colorCount[] = $card->getType();
        }

        if (count($colorCount) == 1) {
            return "flush";
        }
        return "nothing";
    }

    public function straight()
    {
        $numberArray = [];

        foreach($this->hand as $card) {
            $numberArray[] = $card->getNumber();
        }

        $unique_array = array_unique($numberArray);
        arsort($unique_array);

        if ($this->checkIfIsLadder($unique_array)) {
            $this->score = array_sum($unique_array);
            return "straight";
        }

        if ($this->checkIfIsLadderDESC($unique_array)) {
            $this->score = array_sum($unique_array);
            return "straight";
        }
        return "nothing";
    }

    public function threeOfAKind()
    {
        $numbArray = [];

        foreach($this->hand as $card) {
            $numbArray[] = $card->getNumber();
        }

        $vals = array_count_values($numbArray);

        if (max(array_values($vals)) != "3") {
            return "nothing";
        }

        arsort($vals);
        $key = array_key_first($vals);

        if (intval($vals[$key]) == 3) {
            $this->score = intval($vals[$key]) * 3;
            return "threeOfAKind";
        }

        return "nothing";
    }

    public function twoPairs()
    {
        $numbArray = [];
        $count = [];

        foreach($this->hand as $card) {
            $numbArray[] = $card->getNumber();
        }

        $vals = array_count_values($numbArray);
        arsort($vals);

        $first = key($vals);
        next($vals);
        $second = key($vals);

        if (intval($vals[$first]) == 2 && intval($vals[$second]) == 2) {
            $this->score = (intval($vals[$first]) * 2) + (intval($vals[$second]) * 2);
            return "twoPairs";
        }

        return "nothing";
    }

    public function pairs()
    {
        $numbArray = [];
        $count = [];
        for ($i = 0; $i < count($this->hand); $i++)
        {
            $numbArray[] = $this->hand[$i]->getNumber();
        }

        $vals = array_count_values($numbArray);

        $max = max(array_values($vals));
        $key = array_search($max, $vals);

        foreach($vals as $val) {
            if ($val >= 2) {
                $count[] = $val;
            }
        }

        if (!empty($count)) {
            $this->score =  intval($key) * 2;
            return "pairs";
        }
        return "nothing";
    }

    public function highCards()
    {
        $this->score = $this->originalHand[0]->getNumber() + $this->originalHand[1]->getNumber();
        return "highCards";
    }

}