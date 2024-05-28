<?php

namespace App\Project;

class Game
{
    /** @var array<Hand> */
    private array $seats;
    private Hand $dealer;
    /** @var array<Card> */
    private array $deck;

    public bool $inProgress;
    public bool $end;

    /**
     * @param array <mixed> $data.
     */
    public function __construct(array $data)
    {
        $this->deck = $data['deck'] ?? $this->generateDeck();
        $this->inProgress = $data['inProgress'] ?? false;
        $this->end = $data['end'] ?? false;

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
        $this->checkBusted();

        if ($this->allStand() && $this->dealer->getStand()) {
            $this->endOfMatch();
        }
    }

    /**
     * @return array <mixed> $deck.
     */
    private function generateDeck(): array
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

    public function playRound(): void
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

    public function endOfMatch(): void
    {
        $this->end = true;
        $this->setResults();
    }

    /**
     *  @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function setResults(): void
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

            // @phpstan-ignore-next-line
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

    private function checkBusted(): void
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

    public function takeSeat(int $number): void
    {
        $this->seats[$number]->setTaken(true);
    }

    public function hit(int $number): void
    {
        if (!$this->seats[$number]->getTaken() || $this->seats[$number]->getStand()) {
            return;
        }

        $this->seats[$number]->add($this->deal(1));
    }

    public function stand(int $number): void
    {
        $this->seats[$number]->setStand(true);
    }

    /**
     * @return array <mixed>
     */
    private function deal(int $number): array
    {
        return array_splice($this->deck, 1, $number);
    }

    /**
     * @return array <mixed>
     */
    public function getDealer(): array
    {
        return $this->dealer->saveData();
    }

    /**
     * @return array <mixed>
     */
    public function getSeats(): array
    {
        return [
            'seat_1' => $this->seats[1]->saveData(),
            'seat_2' => $this->seats[2]->saveData(),
            'seat_3' => $this->seats[3]->saveData(),
        ];
    }

    /**
     * @return array <mixed>
     */
    public function getSpecificSeat(int $number): array
    {
        $name = "seat_" . $number;
        $seat = $this->seats[$number];

        $cards = [];
        foreach($seat->getCards() as $card) {
            $cards[] = [
                'number' => $card->getNumber(),
                'type' => $card->getType()
            ];
        }

        return [
            'seat' => $name,
            'taken' => $seat->getTaken(),
            'stand' => $seat->getStand(),
            'cards' => $cards,
        ];
    }

    /**
     * @return array <mixed>
     */
    public function dataToTwig(): array
    {
        return [
            'seats' => $this->seats,
            'inProgress' => $this->inProgress,
            'end' => $this->end,
            'dealer' => $this->dealer,
        ];
    }

    /**
     * @return array <mixed>
     */
    public function getMatchStatus(): array
    {
        $seatStatus = [];

        foreach($this->seats as $seat) {
            $res = "";
            if (!$seat->getTaken()) {
                continue;
            }

            $res = $seat->getResult();

            if ($res == "") {
                $res = "No result yet";
            }

            $seatStatus["seat_" . $seat->getIndex()] = [
                'result' => $res,
                'score' => $seat->getScore()
            ];
        }

        return [
            'Round started' => $this->inProgress,
            'Game has ended' => $this->end,
            'seats taken' => $seatStatus,
        ];
    }

    /**
     * @return array <mixed>
     */
    public function makeAllStand(): array
    {
        $array = [];
        foreach($this->seats as $key => $seat) {
            if (!$seat->getTaken()) {
                continue;
            }
            $seat->setStand(true);
            $array["seat_" . $key] = $seat->getStand();
        }

        return $array;
    }

    /**
     * @return array <mixed>
     */
    public function dataToSave(): array
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
