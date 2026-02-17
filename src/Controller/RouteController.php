<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Flag;
use App\Entity\Room;
use App\Repository\RoomRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Repository\UserRepository;
use App\Service\StringManagement;
use Doctrine\ORM\EntityManagerInterface;

class RouteController extends AbstractController {
 

   #[Route('/{difficulty}', name: 'difficulty', requirements: ['difficulty' => 'easy|medium|hard'])]
    public function roomList(string $difficulty, RoomRepository $repo): Response
    {
        $rooms = $repo->findBy(
        ['difficulty' => $difficulty],
        ['number' => 'ASC']
    );

    return $this->render('room/grid.html.twig', [
        'difficulty' => $difficulty,
        'rooms' => $rooms,
    ]);
    }

    #[Route('/{difficulty}/{room}', name: 'room', requirements: [
    'difficulty' => 'easy|medium|hard',
    'room' => '\d+'
    ])]
    public function roomView(string $difficulty, int $room, RoomRepository $roomRepository, EntityManagerInterface $em): Response
    {
    $roomEntity = $roomRepository->findOneBy([
        'number' => $room,
        'difficulty' => $difficulty
    ]);

    if (!$roomEntity) {
        throw $this->createNotFoundException('This challenge room does not exist.');
    }

    $flagEntity = $em->getRepository(Flag::class)->findOneBy(['room' => $roomEntity]);

    return $this->render("room/room.html.twig", [
        'room' => $roomEntity,
        'secretFlag' => $flagEntity ? $flagEntity->getFlag() : 'FLAG_NOT_SET',
    ]);
    }

#[Route('/room/component/render/{id}', name: 'render_component')]
public function renderComponent(Room $room, EntityManagerInterface $em): Response
{
    $flagEntity = $em->getRepository(Flag::class)->findOneBy(['room' => $room]);
    $realFlag = $flagEntity ? $flagEntity->getFlag() : 'DEBUG_NO_FLAG_FOUND';

    $filePath = $this->getParameter('kernel.project_dir') . '/public/uploads/components/' . $room->getComponent();

    if (!file_exists($filePath)) {
        return new Response('File not found: ' . $filePath, 404);
    }

    $content = file_get_contents($filePath);
    $content = str_replace('{{ flag }}', $realFlag, $content);

    return new Response($content);
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
