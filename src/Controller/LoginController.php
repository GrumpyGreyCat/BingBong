<?php

namespace App\Controller;

use App\Entity\Login;
use App\Form\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LoginController extends AbstractController {
    #[Route('/login', name: 'login')]
    public function login(): Response{

        $login = new Login();
        
        $form = $this->createForm(LoginType::class, $login);

        return $this->render('authentification/login.html.twig', ["loginForm" => $form->createView()]);
    }
}