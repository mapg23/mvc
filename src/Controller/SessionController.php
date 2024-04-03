<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class SessionController extends AbstractController
{
    #[Route("/session", name: "session")]
    public function session(
        SessionInterface $session
    ): Response
    {
        $data = [
            "session" => print_r($session->all(), true),
        ];

        return $this->render("session.html.twig", $data);
    }

    #[Route("/session/delete", name: "delete_session")]
    public function session_delete(
        SessionInterface $session
    ) : Response
    {
        $session->clear();

        $this->addFlash(
            'notice',
            'Nu är sessionen raderad'
        );

        return $this->redirectToRoute("session");
    }
}