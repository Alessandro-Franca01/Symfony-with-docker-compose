<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// Classe para testar funcionalidades basicas:
class HelloController extends AbstractController
{
    #[Route('/hello', name: 'app_hello')]
    public function index(): Response
    {
        return $this->render('hello.html.twig', [
            'controller_name' => 'HelloController',
            'mensagem' => "Boot strap 5 works!"
        ]);
    }
}
