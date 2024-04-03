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
            $deck->set_deck($session->get("deck"));
        }

        $deck->sort_cards();

        $session->set("deck", $deck->get_deck());

        $data = ["deck" => $deck->get_deck()];

        return $this->render("card/deck.html.twig", $data);
    }

    #[Route("/card/deck/shuffle", name: "shuffle")]
    public function shuffle(
        SessionInterface $session
    ): Response {
        $deck = new DeckOfCards();

        if ($session->has("deck")) {
            $deck->set_deck($session->get("deck"));
        }

        if ($deck->get_deck_size() === 0) {
            $deck = new DeckOfCards();
        }

        $deck->suffle_deck();


        $session->set("deck", $deck->get_deck());

        $data = [
            "deck" => $deck->get_deck()
        ];

        return $this->render("card/deck.html.twig", $data);
    }

    #[Route("/card/deck/draw", name: "draw")]
    public function draw(
        SessionInterface $session
    ): Response {
        $deck = new DeckOfCards();

        if ($session->has("deck")) {
            $deck->set_deck($session->get("deck"));
        }

        $card = $deck->draw_card();

        $session->set("deck", $deck->get_deck());

        $data = [
            'cards' => $card,
            "count" => $deck->get_deck_size(),
        ];

        return $this->render("card/draw.html.twig", $data);
    }

    #[Route("/card/deck/draw/{num<\d+>}", name: "draw_get")]
    public function draw_multiple(
        int $num,
        SessionInterface $session
    ): Response {
        $deck = new DeckOfCards();

        if ($session->has("deck")) {
            $deck->set_deck($session->get("deck"));
        }

        $card = $deck->draw_card($num);

        $session->set("deck", $deck->get_deck());

        $data = [
            'cards' => $card,
            "count" => $deck->get_deck_size()
        ];

        return $this->render("card/draw.html.twig", $data);
    }
}
