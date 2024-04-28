<?php

namespace App\Controller;

use App\Game\Game;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    /**
     * @param SessionInterface $session.
     * @return array <mixed>
     */
    public function getSessionData(SessionInterface $session): array
    {
        return [
            'deck' => $session->get('game_deck'),
            'p_hand' => $session->get('p_hand'),
            'c_hand' => $session->get('c_hand'),
            'p_stand' => $session->get('p_stand'),
            'c_stand' => $session->get('c_stand'),
            'res' => $session->get('result'),
        ];
    }

    /**
     * @param array<mixed> $data
     */
    public function saveSessionData(SessionInterface $session, array $data): void
    {
        $session->set('game_deck', $data['deck']);
        $session->set('p_hand', $data['p_hand']);
        $session->set('c_hand', $data['c_hand']);
        $session->set('p_stand', $data['p_stand']);
        $session->set('c_stand', $data['c_stand']);
        $session->set('result', $data['res']);
    }

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
        $data = $this->getSessionData($session);
        $game = new Game($data);
        $this->saveSessionData($session, $game->saveData());

        return $this->render("game/game.html.twig", $game->getData());
    }

    #[Route("/game/start/draw", name: "drawCard")]
    public function drawCard(
        SessionInterface $session
    ): Response {
        $data = $this->getSessionData($session);
        $game = new Game($data);
        $game->round();
        $this->saveSessionData($session, $game->saveData());

        return $this->render("game/game.html.twig", $game->getData());
    }

    #[Route("/game/start/stop", name: "stop")]
    public function stop(
        SessionInterface $session
    ): Response {
        $data = $this->getSessionData($session);
        $game = new Game($data);
        $game->stand();
        $this->saveSessionData($session, $game->saveData());
        return $this->render("game/game.html.twig", $game->getData());
    }

    #[Route("/game/start/new_game", name: "newGame")]
    public function newGame(
        SessionInterface $session
    ): Response {
        $data = $this->getSessionData($session);
        $game = new Game($data);
        $game->newMatch();
        $this->saveSessionData($session, $game->saveData());
        return $this->render("game/game.html.twig", $game->getData());
    }
}
