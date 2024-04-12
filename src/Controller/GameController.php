<?php

namespace App\Controller;

use App\Card\CardGraphic;
use App\Card\CardHand;
use App\Card\DeckOfCards;
use App\Game\Game;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    #[Route("/game", name: "game")]
    public function game(): Response
    {
        return $this->render("game/home.html.twig");
    }

    #[Route("/game/doc", name: "doc")]
    public function doc(): Response
    {
        return $this->render("game/doc.html.twig");
    }

    #[Route("/game/start", name: "start")]
    public function startGame(
        SessionInterface $session
    ): Response {
        $game = new Game($session);
        return $this->render("game/game.html.twig", $game->getData());
    }

    #[Route("/game/start/draw", name: "drawCard")]
    public function drawCard(
        SessionInterface $session
    ): Response {
        $game = new Game($session);
        $game->round();
        $data = $game->getData();

        return $this->render("game/game.html.twig", $data);
    }

    #[Route("/game/start/stop", name: "stop")]
    public function stop(
        SessionInterface $session
    ): Response {
        $game = new Game($session);
        $game->stand();
        $data = $game->getData();

        return $this->render("game/game.html.twig", $data);
    }

    #[Route("/game/start/new_game", name: "newGame")]
    public function newGame(
        SessionInterface $session
    ): Response {
        $game = new Game($session);
        $game->newMatch();
        $data = $game->getData();

        return $this->render("game/game.html.twig", $data);
    }
}
