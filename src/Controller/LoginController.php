<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class LoginController extends AbstractController {
    #[Route('/login', name: 'login')]
    public function login(){
        return $this->render('authentification/login.html.twig');
    }
}