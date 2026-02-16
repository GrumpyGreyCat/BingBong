<?php

namespace App\Controller;

use App\Entity\Room;
use App\Entity\Flag;
use App\Entity\Solve;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class SubmitFlagController extends AbstractController {

        #[IsGranted('ROLE_USER')]
        #[Route('/room/submit/{id}', name: 'submitFlag', methods: ['POST'])]
        public function submitFlag(int $id, Request $request, EntityManagerInterface $em): Response
        {
            $room = $em->getRepository(Room::class)->find($id);
            if (!$room) {
                throw $this->createNotFoundException('Room not found');
            }

            $flagEntity = $em->getRepository(Flag::class)->findOneBy(['room' => $id]);
            
            $submittedFlag = $request->request->get('submitted_flag');

            if ($flagEntity && $submittedFlag === $flagEntity->getFlag()) {
                $user = $this->getUser();
                
                $existingSolve = $em->getRepository(Solve::class)->findOneBy([
                    'user' => $user,
                    'room' => $room
                ]);

                if (!$existingSolve) {
                    $solve = new Solve();
                    $solve->setUser($user);
                    $solve->setRoom($room);
                    $solve->setDate(new \DateTime());

                    $em->persist($solve);
                    $em->flush();

                    $this->addFlash('success', 'Correct! Solve recorded.');
                } else {
                    $this->addFlash('info', 'You already solved this room!');
                }
            }
            return $this->redirectToRoute('room', [
                'difficulty' => $room->getDifficulty()->value,
                'room' => $room->getNumber()
            ]);
        }
}