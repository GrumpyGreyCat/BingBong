<?php

namespace App\Controller;

use App\Service\StringManagement;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Entity\User;


class DeleteUserController extends AbstractController {
    
    #[Route('/admin/delUser/{uuidUser}', name:'delUser')]
    #[IsGranted('ROLE_ADMIN')]
    public function delUser($uuidUser, EntityManagerInterface $manager, StringManagement $stringManagement): Response {
        
        if ($stringManagement->isString($uuidUser)) {
            $user = $manager->find(User::class, $uuidUser);
            $manager->remove($user);
            $manager->flush();
        }

        return $this->redirectToRoute('adminUserPanel');
    }
}