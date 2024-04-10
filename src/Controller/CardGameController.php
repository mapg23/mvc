<?php

namespace App\Controller;

use App\Card\CardGraphic;
use App\Card\DeckOfCards;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CardGameController extends AbstractController
{
    #[Route("/card", name: "card", methods: ["GET"])]
    public function card(): Response
    {
        return $this->render("card/card.html.twig");
    }

    #[Route("/card/deck", name: "deck")]
    public function deck(
        SessionInterface $session
    ): Response {
        $deck = new DeckOfCards();

        if ($session->has("deck")) {
            $deck->setDeck($session->get("deck"));
        }

        $deck->sortCards();

        $session->set("deck", $deck->getDeck());

        $data = ["deck" => $deck->getDeck()];

        return $this->render("card/deck.html.twig", $data);
    }

    #[Route("/card/deck/shuffle", name: "shuffle")]
    public function shuffle(
        SessionInterface $session
    ): Response {
        $deck = new DeckOfCards();

        if ($session->has("deck")) {
            $deck->setDeck($session->get("deck"));
        }

        if ($deck->getDeckSize() === 0) {
            $deck = new DeckOfCards();
        }

        $deck->suffleDeck();


        $session->set("deck", $deck->getDeck());

        $data = [
            "deck" => $deck->getDeck()
        ];

        return $this->render("card/deck.html.twig", $data);
    }

    #[Route("/card/deck/draw", name: "draw")]
    public function draw(
        SessionInterface $session
    ): Response {
        $deck = new DeckOfCards();

        if ($session->has("deck")) {
            $deck->setDeck($session->get("deck"));
        }

        $card = $deck->drawCard();

        $session->set("deck", $deck->getDeck());

        $data = [
            'cards' => $card,
            "count" => $deck->getDeckSize(),
        ];

        return $this->render("card/draw.html.twig", $data);
    }

    #[Route("/card/deck/draw/{num<\d+>}", name: "draw_get")]
    public function drawMultiple(
        int $num,
        SessionInterface $session
    ): Response {
        $deck = new DeckOfCards();

        if ($session->has("deck")) {
            $deck->setDeck($session->get("deck"));
        }

        $card = $deck->drawCard($num);

        $session->set("deck", $deck->getDeck());

        $data = [
            'cards' => $card,
            "count" => $deck->getDeckSize()
        ];

        return $this->render("card/draw.html.twig", $data);
    }
}
