<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

class ScoreboardController extends AbstractController {

    #[Route('/scoreboard', name: 'scoreboard')]
    public function scoreboard(EntityManagerInterface $em): Response
    {

        $scores = $em->createQuery(
        'SELECT u.email, 
                COUNT(s.id) as totalSolved,
                SUM(CASE WHEN r.difficulty = \'easy\' THEN 1 ELSE 0 END) as easySolved,
                SUM(CASE WHEN r.difficulty = \'medium\' THEN 1 ELSE 0 END) as mediumSolved,
                SUM(CASE WHEN r.difficulty = \'hard\' THEN 1 ELSE 0 END) as hardSolved,
                SUM(CASE 
                    WHEN r.difficulty = \'easy\' THEN 10 
                    WHEN r.difficulty = \'medium\' THEN 20
                    WHEN r.difficulty = \'hard\' THEN 30
                    ELSE 0 
                END) as totalPoints, 
                MAX(s.date) as lastSolve
        FROM App\Entity\Solve s
        JOIN s.user u
        JOIN s.room r
        GROUP BY u.id
        ORDER BY totalPoints DESC, lastSolve ASC'
    )->getResult();

    return $this->render('scoreboard/mainScoreboard.html.twig', [
        'scores' => $scores,
    ]);

    }
}