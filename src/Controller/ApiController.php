<?php

namespace App\Controller;

use App\Card\DeckOfCards;
use App\Game\Game;
use App\Controller\GameController;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    #[Route("/api", name: "api")]
    public function api(): Response
    {
        return $this->render("api.html.twig");
    }

    #[Route("/api/quote", name: "quote")]
    public function quote(): Response
    {
        $numb = random_int(0, 2);

        $quotes = array(
            "Code dosent lie, comments sometimes do.",
            "Good software, like wine, takes time.",
            "If, at first, you do not succeed, call it version 1.0."
        );

        $data = [
            'quote' => $quotes[$numb],
            'date' => date("Y-m-d"),
            'generated' => date('Y-m-d H:i:s', time())
        ];

        $response = new Response();

        $parsedData = json_encode($data) ?: null;

        $response->setContent($parsedData);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    #[Route("/api/deck", name: "api_deck", methods: ["GET"])]
    public function apiDeck(
        SessionInterface $session
    ): Response {
        $deck = new DeckOfCards();

        if ($session->has("deck")) {
            $deck->setDeck($session->get("deck"));
        }

        $deck->sortCards();

        $data = [ $deck->getDeck() ];

        $response = new Response();
        $parsedData = json_encode($data) ?: null;
        $response->setContent($parsedData);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    #[Route("/api/deck/shuffle", name: "api_deck_shuffle", methods: ["GET", "POST"])]
    public function apiDeckShuffle(
        SessionInterface $session
    ): Response {
        $deck = new DeckOfCards();

        if ($session->has("deck")) {
            $deck->setDeck($session->get("deck"));
        }

        $deck->suffleDeck();

        $data = [ $deck->getDeck() ];

        $response = new Response();
        $parsedData = json_encode($data) ?: null;
        $response->setContent($parsedData);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    #[Route("/api/deck/draw", name: "api_deck_draw", methods: ["GET", "POST"])]
    public function apiDeckDraw(
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

        $response = new Response();
        $parsedData = json_encode($data) ?: null;
        $response->setContent($parsedData);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    #[Route("/api/deck/draw/{number<\d+>}", name: "api_deck_draw_multiple")]
    public function apiDeckDrawMultiple(
        int $number,
        SessionInterface $session
    ): Response {
        $deck = new DeckOfCards();

        if ($session->has("deck")) {
            $deck->setDeck($session->get("deck"));
        }

        $card = $deck->drawCard($number);

        $session->set("deck", $deck->getDeck());

        $data = [
            'cards' => $card,
            "Cards remaining" => $deck->getDeckSize()
        ];

        $response = new Response();
        $parsedData = json_encode($data) ?: null;
        $response->setContent($parsedData);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }


    #[Route("/api/game", name: "api_game", methods: ["GET"])]
    public function apiGame(
        SessionInterface $session
    ): Response {

        $gameController = new GameController();

        $game = new Game($gameController->getSessionData($session));

        $data = $game->getData();

        $response = new Response();
        $parsedData = json_encode($data) ?: null;
        $response->setContent($parsedData);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }


}
