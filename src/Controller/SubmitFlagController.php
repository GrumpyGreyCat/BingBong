<?php

namespace App\Controller;

use App\Entity\Room;
use App\Entity\Flag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

class SubmitFlagController extends AbstractController {


        #[Route('/room/submit/{id}', name: 'submitFlag', methods: ['POST'])]
        public function submitFlag(int $id, Request $request, EntityManagerInterface $em): Response
        {
            // 1. Find the room
            $room = $em->getRepository(Room::class)->find($id);
            if (!$room) {
                throw $this->createNotFoundException('Room not found');
            }

            // 2. Find the flag for this room
            $flagEntity = $em->getRepository(Flag::class)->findOneBy(['room' => $id]);
            
            // 3. Get the user's input from the form
            $submittedFlag = $request->request->get('submitted_flag');

            // 4. Check if it matches
            if ($flagEntity && $submittedFlag === $flagEntity->getFlag()) {
                $this->addFlash('success', '🚩 Correct! You found the flag!');
            } else {
                $this->addFlash('error', '❌ Wrong flag, try again.');
            }

            // 5. Redirect back to the room view
            return $this->redirectToRoute('room', [
                'difficulty' => $room->getDifficulty()->value,
                'room' => $room->getNumber()
            ]);
        }
}