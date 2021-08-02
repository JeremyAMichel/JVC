<?php

namespace App\Controller;

use App\Repository\GameRepository;
use App\Service\ForumService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{

    /**
     * @var GameRepository
     */
    private $gr;

    /**
     * @var ForumService
     */
    private $fs;

    public function __construct(GameRepository $gr, ForumService $fs)
    {
        $this->gr=$gr;
        $this->fs=$fs;
    }
    
    /**
     * @Route("/game", name="game")
     */
    public function game(): Response
    {
        $games=$this->gr->findAll();
        return $this->render('game/index.html.twig', [
            'controller_name' => 'GameController',
            'games' => $games
        ]);
    }
}
