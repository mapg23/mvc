<?php

namespace App\Project;

class Game
{

    private array $seats;
    private Hand $dealer;
    private array $deck;

    private bool $inProgress;
    private bool $end;
    private array|string $dbg;

    public function __construct(array $data)
    {
        $this->deck = $data['deck'] ?? $this->generateDeck();
        $this->inProgress = $data['inProgress'] ?? false;
        $this->end = $data['end'] ?? false;

        $this->dbg = $data;

        $this->dealer = new Hand(
            0,
            true,
            $data['dealer']['cards'] ?? $this->deal(2),
            $data['dealer']['stand'] ?? false,
            $data['dealer']['result'] ?? "",
        );
        
        $this->seats = [
            1 => new Hand(
                1,
                $data['seat_1']['taken'] ?? false,
                $data['seat_1']['cards'] ?? $this->deal(2),
                $data['seat_1']['stand'] ?? false,
                $data['seat_1']['result'] ?? "",
            ),
            2 => new Hand(
                2,
                $data['seat_2']['taken'] ?? false,
                $data['seat_2']['cards'] ?? $this->deal(2),
                $data['seat_2']['stand'] ?? false,
                $data['seat_2']['result'] ?? "",
            ),
            3 => new Hand(
                3,
                $data['seat_3']['taken'] ?? false,
                $data['seat_3']['cards'] ?? $this->deal(2),
                $data['seat_3']['stand'] ?? false,
                $data['seat_3']['result'] ?? "",
            )
        ];
        $this->init();
    }

    public function init()
    {
        $this->checkBusted();

        if ($this->allStand() && $this->dealer->getStand()) {
            $this->endOfMatch();
        }
    }

    public function playRound()
    {
        $this->inProgress = true;

        if ($this->allStand() && $this->dealer->getStand()) {
            $this->endOfMatch();
            return;
        }
        
        if($this->dealer->getScore() >= 17) {
            $this->dealer->setStand(true);
            return;
        }
        
        $this->dealer->add($this->deal(1));
        return;
    }
    
    public function endOfMatch()
    {
        $this->end = true;
        $this->setResults();
    }

    public function setResults()
    {
        $dealerScore = $this->dealer->getScore();

        foreach($this->seats as $seat) {
            if (!$seat->getTaken()) {
                continue;
            }

            $seatScore = $seat->getScore();

            if ($seatScore > 21) {
                $seat->setResult("Lose");
                $this->dealer->setResult("Win");
                continue;
            }
            
            if ($seatScore > $dealerScore && $seatScore < 22) {
                $seat->setResult("Win");
                $this->dealer->setResult("Lose");
                continue;
            }
            
            if ($seatScore < $dealerScore && $dealerScore > 21) {
                $seat->setResult("Win");
                $this->dealer->setResult("Lose");
                continue;
            }
            
            if ($seatScore < $dealerScore) {
                $seat->setResult("Lose");
                $this->dealer->setResult("Win");
                continue;
            }
            
            if ($seatScore == $dealerScore) {
                $this->dealer->setResult("Tie");
                $seat->setResult("Tie");
                continue;
            }
        }
    }


    public function allStand(): bool
    {
        foreach($this->seats as $seat) {
            if ($seat->getTaken() && !$seat->getStand()) {
                return false;
            }
        }
        return true;
    }

    public function checkBusted()
    {
        foreach($this->seats as $seat) {
            if ($seat->getScore() >= 22) {
                $seat->setStand(true);
            }
        }

        if($this->dealer->getScore() >= 22) {
            $this->dealer->setStand(true);
        }
    }

    public function takeSeat(int $number)
    {
        $this->seats[$number]->setTaken(true);
    }

    public function hit(int $number)
    {
        $this->seats[$number]->add($this->deal(1));
    }

    public function stand(int $number)
    {
        $this->seats[$number]->setStand(true);
    }

    public function generateDeck(): array
    {
        $types = ["spades", "hearts", "diamonds", "clubs"];
        $deck = [];

        foreach($types as $type) {
            for ($i = 2; $i < 15; $i++) {
                $deck[] = new Card($type, $i);
            }
        }
        shuffle($deck);

        return $deck;
    }

    public function deal(int $number)
    {
        return array_splice($this->deck, 1, $number);
    }


    public function dataToTwig(): array
    {
        return [
            'seats' => $this->seats,
            'inProgress' => $this->inProgress,
            'end' => $this->end,
            'dealer' => $this->dealer,
            'debug' => $this->dbg,
        ];
    }
    
    public function dataToSave()
    {
        return [
            'inProgress' => $this->inProgress,
            'end' => $this->end,
            'seat_1' => $this->seats[1]->saveData(),
            'seat_2' => $this->seats[2]->saveData(),
            'seat_3' => $this->seats[3]->saveData(),
            'dealer' => $this->dealer->saveData(),
        ];
    }
}

