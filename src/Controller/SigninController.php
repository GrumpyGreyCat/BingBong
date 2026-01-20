<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\SigninType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SigninController extends AbstractController {
    
    #[Route('/signin', name: 'signin')]
    public function signin(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response {
        
        $user = new User();
        $form = $this->createForm(SigninType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $plainPassword = $user->getPassword();

            $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
            
            $user->setPassword($hashedPassword);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Registration successful!');
            return $this->redirectToRoute('login');
        }

        return $this->render('authentification/signin.html.twig', [
            "signinForm" => $form->createView()
        ]);
    }
}