<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

use App\Project\Game;

class ProjectController extends AbstractController
{
    /**
     * Metod to return session data.
     * @param SessionInterface $session.
     * @return array <mixed>
     */
    public function getSessionData(SessionInterface $session): array
    {
        return [
            'seat_1' => $session->get('seat_1'),
            'seat_2' => $session->get('seat_2'),
            'seat_3' => $session->get('seat_3'),
            'dealer' => $session->get('dealer'),
            'inProgress' => $session->get('inProgress'),
            'end' => $session->get('end'),
        ];
    }

    /**
     * Metod to save data to session.
     * @param SessionInterface $session.
     * @param array <mixed> $data.
     */
    public function saveSessionData(SessionInterface $session, array $data): void
    {
        $session->set('seat_1', $data['seat_1']);
        $session->set('seat_2', $data['seat_2']);
        $session->set('seat_3', $data['seat_3']);
        $session->set('dealer', $data['dealer']);
        $session->set('inProgress', $data['inProgress']);
        $session->set('end', $data['end']);
    }

    #[Route("/proj", name: "project")]
    public function index(): Response
    {
        return $this->render("proj/about.html.twig");
    }

    #[Route("/proj/about", name: "project_about")]
    public function about(): Response
    {
        return $this->render("proj/about.html.twig");
    }

    #[Route("/proj/rules", name: "project_rules")]
    public function rules(): Response
    {
        return $this->render("proj/rules.html.twig");
    }

    #[Route("/proj/game", name: "project_game")]
    public function game(
        SessionInterface $session
    ): Response {
        $data = $this->getSessionData($session);
        $game = new Game($data);

        $output = $game->dataToTwig();
        $saveData = $game->dataToSave();

        $this->saveSessionData($session, $saveData);

        return $this->render("proj/game.html.twig", $output);
    }

    #[Route("/proj/join/{number<\d+>}", name: "project_join_seat")]
    public function addSeat(
        int $number,
        SessionInterface $session
    ): Response {
        $data = $this->getSessionData($session);
        $game = new Game($data);
        $game->takeSeat($number);
        $saveData = $game->dataToSave();
        $this->saveSessionData($session, $saveData);

        return $this->redirectToRoute('project_game');
    }

    #[Route("/proj/hit/{number<\d+>}", name: "project_hit")]
    public function hit(
        int $number,
        SessionInterface $session
    ): Response {
        $data = $this->getSessionData($session);
        $game = new Game($data);
        $game->hit($number);
        $saveData = $game->dataToSave();
        $this->saveSessionData($session, $saveData);

        return $this->redirectToRoute('project_game');
    }

    #[Route("/proj/stand/{number<\d+>}", name: "project_stand")]
    public function stand(
        int $number,
        SessionInterface $session
    ): Response {
        $data = $this->getSessionData($session);
        $game = new Game($data);
        $game->stand($number);
        $saveData = $game->dataToSave();
        $this->saveSessionData($session, $saveData);

        return $this->redirectToRoute('project_game');
    }

    #[Route("/proj/next_round", name: "project_next_round")]
    public function nextRound(
        SessionInterface $session
    ): Response {
        $data = $this->getSessionData($session);
        $game = new Game($data);
        $game->playRound();
        $saveData = $game->dataToSave();
        $this->saveSessionData($session, $saveData);

        return $this->redirectToRoute('project_game');
    }

    #[Route("/proj/new_game", name: "project_new_game")]
    public function newGame(
        SessionInterface $session
    ): Response {
        $session->clear();
        return $this->redirectToRoute('project_game');
    }
}
