<?php

namespace App\Twig;

use App\Entity\Coordonnee;
use App\Entity\Game;
use App\Entity\Message;
use App\Repository\CoordonneeRepository;
use App\Repository\GameRepository;
use App\Repository\PostRepository;
use App\Service\ForumService;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{

    /**
     * @var ForumService
     */
    private $fs;

    /**
     * @var Environment
     */
    private $twigEnvironment;

    /**
     * @var GameRepository
     */
    private $gameRepository;

    /**
     * @var PostRepository
     */
    private $postRepository;

    /**
     * @var CoordonneeRepository
     */
    private $cr;

    public function __construct(ForumService $fs, Environment $twigEnvironment, GameRepository $gameRepository,
    PostRepository $postRepository, CoordonneeRepository $cr)
    {
        $this->fs=$fs;
        $this->twigEnvironment=$twigEnvironment;
        $this->gameRepository=$gameRepository;
        $this->postRepository=$postRepository;
        $this->cr=$cr;
    }
    
    public function getFunctions()
    {
        return [
            new TwigFunction('lastMessage', [$this, 'lastMessageOfTopic']),
            new TwigFunction('gameStarNbr', [$this, 'gameStarNumber']),
            new TwigFunction('nbGame', [$this, 'nbGame']),
            new TwigFunction('nbPost', [$this, 'nbPost']),
            new TwigFunction('coordonnee', [$this, 'coordonnee']),
        ];
    }

    public function lastMessageOfTopic(int $idTopic)
    {
        $lastMessage=$this->fs->getLastMessageOfTopic($idTopic);
        if($lastMessage !== null){
            return $lastMessage->getCreatedAt()->format('Y-m-d H:i');
        } else return 'Aucun Message';

        return $lastMessage;
    }

    public function gameStarNumber(Game $game)
    {
        
        $nbrStar=$game->getNoteGlobal();
        $otherStar=10-$nbrStar;
        return $this->twigEnvironment->render('partial/game.html.twig',[
            'nbrStar'=>$nbrStar,
            'otherStar'=>$otherStar,
            'game'=>$game
        ]);
    }

    public function nbGame():int
    {
        return count($this->gameRepository->findAll());
    }

    public function nbPost():int
    {
        return count($this->postRepository->findAll());
    }

    public function coordonnee():Coordonnee
    {
        return $this->cr->find(1);
    }
}