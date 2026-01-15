<?php

namespace App\Controller;

use App\Entity\Signin;
use App\Form\SigninType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SigninController extends AbstractController {
    #[Route('/signin', name: 'signin')]
    public function signin(): Response{

        $signin = new Signin();
        
        $form = $this->createForm(SigninType::class, $signin);

        return $this->render('authentification/signin.html.twig', ["signinForm" => $form->createView()]);
    }
}