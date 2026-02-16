<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Repository\UserRepository;
use App\Service\StringManagement;
use Doctrine\ORM\EntityManagerInterface;

class RouteController extends AbstractController {
 

   #[Route('/{difficulty}', name: 'difficulty', requirements: ['difficulty' => 'easy|medium|hard'])]
    public function roomList(string $difficulty): Response
    {
        return $this->render("room/grid.html.twig", [
            'difficulty' => $difficulty,
        ]);
    }

    #[Route('/{difficulty}/{room}', name: 'room', requirements: [
    'difficulty' => 'easy|medium|hard',
    'room' => '\d+'
    ])]

    public function roomView(string $difficulty, int $room): Response
    {

        return $this->render("room/room.html.twig", [
            'difficulty' => $difficulty,
            'room_id'    => $room,
        ]);
    }

    #[Route('/admin/users', name: 'adminUserPanel')]
    #[IsGranted('ROLE_ADMIN')]
    public function userGrid(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('admin/adminUserPanel.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/profile/{uuidUser}', name: 'profilePage')]
    public function profilePage(string $uuidUser, StringManagement $stringManagement, EntityManagerInterface $manager): Response
    {
        $user = null;

        if ($stringManagement->isString($uuidUser)) {
            $user = $manager->find(User::class, $uuidUser);
        }

        if (!$user) {
            throw $this->createNotFoundException('User not found.');
        }

        $authenticatedUser = $this->getUser();

        if (!in_array('ROLE_ADMIN', $authenticatedUser->getRoles()) && $authenticatedUser !== $user) {
            throw $this->createAccessDeniedException('You are not allowed to view this profile.');
        }

        return $this->render('authentication/profile.html.twig', [
            'user' => $user,
        ]);
    }

}
