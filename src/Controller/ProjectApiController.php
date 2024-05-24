<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

use App\Project\Game;

class ProjectApiController extends AbstractController
{

    #[Route("/proj/api", name: "project_api")]
    public function api(): Response
    {
        return $this->render("proj/api.html.twig");
    }

    #[Route("/proj/api/draw", name : "project_api_draw")]
    public function draw()
    {
        
    }
}