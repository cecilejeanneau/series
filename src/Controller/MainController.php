<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/home', name: 'main_home')]
    #[Route('/')]
    public function home(): Response
    {
//        return  new Response("<h1>Hello world ! </h1>");
        return $this->render('main/home.html.twig');
    }

    #[Route('/test', name: 'main_test')]
    public function test(): Response
    {
//        return  new Response("<h1>Test ! </h1>");
        return $this->render('main/test.html.twig');
    }
}
