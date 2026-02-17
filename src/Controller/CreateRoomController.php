<?php

namespace App\Controller;

use App\Entity\Room;
use App\Entity\Flag;
use App\Form\RoomType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CreateRoomController extends AbstractController
{
    #[Route('/admin/createRoom', name: 'createRoom')]
    #[IsGranted('ROLE_ADMIN')]
    public function createRoom(Request $request, EntityManagerInterface $entityManager): Response
    {
        $room = new Room();
        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $componentFile */
            $componentFile = $form->get('component')->getData();

            if ($componentFile) {
                $destination = $this->getParameter('components_directory');
                $newFilename = uniqid().'-'.$room->getNumber().'.'.$componentFile->guessExtension();

                try {
                    $componentFile->move($destination, $newFilename);
                    $room->setComponent($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Failed to upload file: ' . $e->getMessage());
                    return $this->render('/admin/createRoom.html.twig', [
                        'roomForm' => $form->createView(),
                        'difficulty' => \App\Enum\Difficulty::cases(),
                    ]);
                }
            }

            $entityManager->persist($room);

            $flagEntity = new Flag();
            $randomSecret = bin2hex(random_bytes(16));
            $generatedFlag = "FLAG{" . $randomSecret . "}";
            
            $flagEntity->setFlag($generatedFlag);
            $flagEntity->setRoom($room);
            $entityManager->persist($flagEntity);

            $entityManager->flush();

            $this->addFlash('success', 'Room #' . $room->getNumber() . ' created successfully! Flag generated: '. $generatedFlag);

            return $this->redirectToRoute('home'); 
        }

        return $this->render('/admin/createRoom.html.twig', [
            'roomForm' => $form->createView(),
            'edit_mode' => false
        ]);
    }
}