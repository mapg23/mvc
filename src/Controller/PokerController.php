<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

use App\Project\Card;
use App\Project\Game;
use App\Project\Rules;

class PokerController extends AbstractController
{
    public function getSessionData(SessionInterface $session): array
    {
        return [
            'deck' => $session->get('deck'),
            'dealer' => $session->get('dealer'),
            'pot' => $session->get('pot'),
            'round' => $session->get('round'),

            'player1' => [
                'hand' => $session->get('p1_hand'),
                'fold' => $session->get('p1_fold'),
                'money' => $session->get('p1_money'),
                'bet' => $session->get('p1_bet'),
            ],

            'player2' => [
                'hand' => $session->get('p2_hand'),
                'fold' => $session->get('p2_fold'),
                'money' => $session->get('p2_money'),
                'bet' => $session->get('p2_bet'),
            ],

            'player3' => [
                'hand' => $session->get('p3_hand'),
                'fold' => $session->get('p3_fold'),
                'money' => $session->get('p3_money'),
                'bet' => $session->get('p3_bet'),
            ],
        ];
    }

    public function saveSessionData(SessionInterface $session, array $data): void
    {
        $session->set('deck', $data['deck']);
        $session->set('dealer', $data['dealer']);
        $session->set('pot', $data['pot']);
        $session->set('round', $data['round']);

        $session->set('p1_hand', $data['player1']['hand']);
        $session->set('p1_fold', $data['player1']['fold']);
        $session->set('p1_money', $data['player1']['money']);
        $session->set('p1_bet', $data['player1']['bet']);

        $session->set('p2_hand', $data['player2']['hand']);
        $session->set('p2_fold', $data['player2']['fold']);
        $session->set('p2_money', $data['player2']['money']);
        $session->set('p2_bet', $data['player2']['bet']);

        $session->set('p3_hand', $data['player3']['hand']);
        $session->set('p3_fold', $data['player3']['fold']);
        $session->set('p3_money', $data['player3']['money']);
        $session->set('p3_bet', $data['player3']['bet']);
    }

    #[Route("/proj", name: "project")]
    public function index(): Response
    {
        return $this->render("proj/index.html.twig");
    }

    #[Route("proj/game", name: "project_game")]
    public function game(
        SessionInterface $session
    ): Response
    {
        $data = $this->getSessionData($session);
        $game = new Game($data);

        $output = $game->getDataForTwig();

        $saveData = $game->saveDataToSession();
        $this->saveSessionData($session, $saveData);
        return $this->render("proj/board.html.twig", $output);
    }

    #[Route("proj/next_round", name: "project_game_next")]
    public function newRound(
        SessionInterface $session
    ): Response
    {
        $data = $this->getSessionData($session);
        $data['round']++;

        $game = new Game($data);
        
        $output = $game->getDataForTwig();
        $saveData = $game->saveDataToSession();
        $this->saveSessionData($session, $saveData);
        
        return $this->render("proj/board.html.twig", $output);
    }
}