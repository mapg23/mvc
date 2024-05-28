<?php

namespace App\Project;

class Hand
{
    private bool $taken = false;
    /** @var array<Card> */
    private array $cards;
    private int $index;
    private bool $stand;
    private string $result;

    /**
     * @param array <mixed> $cards.
     */
    public function __construct(
        int $index,
        bool $taken,
        array $cards,
        bool $stand,
        string $result,
    ) {
        $this->index = $index;
        $this->taken = $taken;
        $this->cards = $cards;
        $this->stand = $stand;
        $this->result = $result;
    }

    /**
     * @param array <mixed> $card.
     */
    public function add(array $card): void
    {
        $this->cards[] = $card[0];
    }

    public function getScore(): int
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

    public function getIndex(): int
    {
        return $this->index;
    }

    /**
     * @param array <mixed> $cards.
     */
    public function setCards(array $cards): void
    {
        $this->cards = $cards;
    }

    /**
     * @return array <mixed>
     */
    public function getCards(): array
    {
        return $this->cards;
    }

    public function getTaken(): bool
    {
        return $this->taken;
    }

    public function setTaken(bool $param): void
    {
        $this->taken = $param;
    }

    public function setStand(bool $param): void
    {
        $this->stand = $param;
    }

    public function getStand(): bool
    {
        return $this->stand;
    }

    public function setResult(string $param): void
    {
        $this->result = $param;
    }

    public function getResult(): string
    {
        return $this->result;
    }

    /**
     * @return array <mixed>
     */
    public function saveData(): array
    {
        return [
            'cards' => $this->getCards(),
            'taken' => $this->getTaken(),
            'stand' => $this->getStand(),
            'result' => $this->getResult()
        ];
    }
}
