<?php

namespace App\Controller;

use App\Card\DeckOfCards;

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
        $response->setContent(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    #[Route("/api/deck", name: "api_deck", methods: ["GET"])]
    public function api_deck(
        SessionInterface $session
    ) : Response
    {
        $deck = new DeckOfCards();

        if ($session->has("deck")) {
            $deck->set_deck($session->get("deck"));
        }

        $deck->sort_cards();

        $data = [ $deck->get_deck() ];

        $response = new Response();
        $response->setContent(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    #[Route("/api/deck/shuffle", name: "api_deck_shuffle", methods: ["GET", "POST"])]
    public function api_deck_shuffle(
        SessionInterface $session
    ) :Response
    {
        $deck = new DeckOfCards();

        if ($session->has("deck")) {
            $deck->set_deck($session->get("deck"));
        }

        $deck->suffle_deck();

        $data = [ $deck->get_deck() ];

        $response = new Response();
        $response->setContent(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    #[Route("/api/deck/draw", name: "api_deck_draw", methods: ["GET", "POST"])]
    public function api_deck_draw(
        SessionInterface $session
    ) : Response
    {

    }
}
