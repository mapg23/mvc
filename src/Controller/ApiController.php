<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route; 

class ApiController extends AbstractController
{

    #[Route("/api", name: "api")]
    public function api() : Response
    {
        return $this->render("api.html.twig");
    }

}