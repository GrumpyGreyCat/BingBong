<?php

namespace App\Controller;

use App\Entity\Solve;
use App\Entity\Room;
use App\Entity\Flag;
use App\Form\RoomType;
use App\Repository\RoomRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class RoomManagementController extends AbstractController {


    #[Route('/admin/roomManagement', name: 'roomManagement')]
    #[IsGranted('ROLE_ADMIN')]
    public function manageRooms(RoomRepository $roomRepository, EntityManagerInterface $em): Response
    {
        $rooms = $roomRepository->findAll();
        
        $solveCounts = [];
        foreach ($rooms as $room) {
            $solveCounts[$room->getId()] = $em->getRepository(Solve::class)->count(['room' => $room]);
        }

        return $this->render('admin/roomManagement.html.twig', [
            'rooms' => $rooms,
            'solveCounts' => $solveCounts
        ]);
    }


    #[Route('/admin/room/delete/{id}', name: 'roomDelete')]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteRoom(Room $room, EntityManagerInterface $em, string $uploadDir): Response
    {
        $filename = $room->getComponent();

        if ($filename) {
        $filePath = $uploadDir . '/' . $filename;
        if (file_exists($filePath)) {
            unlink($filePath);
            }
        }

        $flag = $em->getRepository(Flag::class)->findOneBy(['room' => $room]);
            if ($flag) 
                { $em->remove($flag); }

           
            $solves = $em->getRepository(Solve::class)->findBy(['room' => $room]);
            foreach ($solves as $solve) { $em->remove($solve); }

        $em->remove($room);
        $em->flush();

        return $this->redirectToRoute('roomManagement');
    }

    #[Route('/admin/room/edit/{id}', name: 'roomEdit')]
    #[IsGranted('ROLE_ADMIN')]
    public function editRoom(Room $room, Request $request, EntityManagerInterface $em, string $uploadDir): Response
    {
        $oldFilename = $room->getComponent();
        
        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('component')->getData();

            if ($file) {
        
                $newFilename = uniqid().'.'.$file->guessExtension();
                $file->move($uploadDir, $newFilename);

                if ($oldFilename) {
                    $oldFilePath = $uploadDir . '/' . $oldFilename;
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }

                $room->setComponent($newFilename);
            } else {
            
                $room->setComponent($oldFilename);
            }

            $em->flush();
            $this->addFlash('success', 'Room updated successfully!');
            return $this->redirectToRoute('roomManagement');
        }

        return $this->render('admin/createRoom.html.twig', [
            'roomForm' => $form->createView(),
            'room' => $room,
            'edit_mode' => true
        ]);
    }
}