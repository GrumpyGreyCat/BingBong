<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Repository\UserRepository;

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
    'room' => '\d+' // This ensures 'room' must be a number
    ])]
    public function roomView(string $difficulty, int $room): Response
    {
        // In a real app, you'd fetch the specific challenge data here
        // $challenge = $repository->findOneBy(['difficulty' => $difficulty, 'id' => $room]);

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


    
}
