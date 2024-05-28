<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Project\Game;
use App\Controller\ProjectController;

class ProjectApiController extends AbstractController
{
    #[Route("/proj/api", name: "project_api")]
    public function api(): Response
    {
        return $this->render("proj/api.html.twig");
    }

    #[Route("/proj/api/draw", name : "project_api_draw", methods: ['POST'])]
    public function draw(
        Request $request,
        SessionInterface $session
    ): Response {
        $requestData = $request->request->all();
        $projectController = new ProjectController();
        $gameData = $projectController->getSessionData($session);

        $game = new Game($gameData);
        $game->hit(intval($requestData['seat_number']));

        $data = $game->getSpecificSeat(intval($requestData['seat_number']));

        $response = new Response();

        $parsedData = json_encode($data) ?: null;

        $response->setContent($parsedData);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    #[Route("/proj/api/dealer", name : "project_api_dealer")]
    public function dealer(SessionInterface $session): Response
    {
        $projectController = new ProjectController();
        $gameData = $projectController->getSessionData($session);
        $game = new Game($gameData);
        $data = $game->getDealer();

        $response = new Response();

        $parsedData = json_encode($data) ?: null;

        $response->setContent($parsedData);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    #[Route("/proj/api/player", name : "project_api_player")]
    public function player(SessionInterface $session): Response
    {
        $projectController = new ProjectController();
        $gameData = $projectController->getSessionData($session);
        $game = new Game($gameData);
        $data = $game->getSeats();

        $response = new Response();

        $parsedData = json_encode($data) ?: null;

        $response->setContent($parsedData);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    #[Route("/proj/api/match_status", name: "project_api_match_details")]
    public function matchStatus(SessionInterface $session): Response
    {
        $projectController = new ProjectController();
        $gameData = $projectController->getSessionData($session);
        $game = new Game($gameData);
        $data = $game->getMatchStatus();

        $response = new Response();

        $parsedData = json_encode($data) ?: null;

        $response->setContent($parsedData);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    #[Route("/proj/api/stand", name: "project_api_stand")]
    public function stand(SessionInterface $session): Response
    {
        $projectController = new ProjectController();
        $gameData = $projectController->getSessionData($session);
        $game = new Game($gameData);
        $data = $game->makeAllStand();

        $response = new Response();

        $parsedData = json_encode($data) ?: null;

        $response->setContent($parsedData);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
