<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

class LevelController extends AbstractController {
    #[Route('/easy', name: 'easy')]
    public function easy(): Response{
        return $this->render('level/easy.html.twig');
    }

    #[Route('/medium', name: 'medium')]
    public function medium(): Response{
        return $this->render('level/medium.html.twig');
    }

    #[Route('/hard', name: 'hard')]
    public function hard(): Response{
        return $this->render('level/hard.html.twig');
    }
}
