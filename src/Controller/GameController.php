<?php

namespace App\Controller;

use App\Card\CardGraphic;
use App\Card\DeckOfCards;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{

    #[Route("/game", name: "game")]
    public function game() {
        return $this->render("game/home.html.twig");
    }

    #[Route("/game/doc", name: "doc")]
    public function doc() {
        return $this->render("game/doc.html.twig");
    }

    // #[Route("/card/deck/draw/{num<\d+>}", name: "draw_get")]
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
