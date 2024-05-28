<?php

namespace App\Project;

use App\Project\Hand;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Test cases for class Hand.
 */
class HandTest extends TestCase
{
    public function testCreated(): void
    {
        $hand = new Hand(1, false, array(), false, "");

        $this->assertInstanceOf("\App\Project\Hand", $hand);
    }

    public function testGetIndex(): void
    {
        $hand = new Hand(1, false, array(), false, "");
        $this->assertEquals(1, $hand->getIndex());
        $hand = new Hand(2, false, array(), false, "");
        $this->assertEquals(2, $hand->getIndex());
    }

    public function testGetScore(): void
    {
        $hand = new Hand(
            1,
            true,
            array(
                new Card('diamonds', 14),
                new Card('diamonds', 10),
                new Card('diamonds', 2),
            ),
            false,
            ""
        );
        $this->assertEquals(13, $hand->getScore());

        $hand = new Hand(
            1,
            true,
            array(
                new Card('diamonds', 5),
                new Card('diamonds', 2),
                new Card('diamonds', 14),
            ),
            false,
            ""
        );

        $this->assertEquals(18, $hand->getScore());
    }

    public function testSetCards(): void
    {
        $hand = new Hand(
            1,
            true,
            array(),
            false,
            ""
        );

        $hand->setCards(array(new Card("hearts", 2)));

        $this->assertEquals(array(new Card("hearts", 2)), $hand->getCards());
    }
}
