<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UpdateUserType;
use App\Service\StringManagement;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UpdateUserController extends AbstractController {
    
    #[Route('/updateUser/{uuidUser}', name: 'updateUser')]
    public function updateUser($uuidUser, Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $passwordHasher, StringManagement $stringManagement): Response {
        
        $user = null;

        if ($stringManagement->isString($uuidUser)) {
            $user = $manager->find(User::class, $uuidUser);
        }

        if (!$user) {
            throw $this->createNotFoundException('User not found.');
        }

        $authenticatedUser = $this->getUser();

        if (!in_array('ROLE_ADMIN', $authenticatedUser->getRoles()) && $authenticatedUser !== $user) {
            throw $this->createAccessDeniedException('You are not allowed to edit this profile.');
        }
        $form = $this->createForm(UpdateUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('password')->getData();

            if ($plainPassword) {
                
                $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
                $user->setPassword($hashedPassword);
            }

            $manager->flush();

            $this->addFlash('success', 'Registration successful!');
            return $this->redirectToRoute('login');
        
        }

        return $this->render('authentification/updateUser.html.twig', [
            "updateUserForm" => $form->createView()
        ]);
    }
}