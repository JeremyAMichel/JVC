<?php

namespace App\Twig;

use App\Entity\Game;
use App\Service\ForumService;
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

    public function __construct(ForumService $fs, Environment $twigEnvironment)
    {
        $this->fs=$fs;
        $this->twigEnvironment=$twigEnvironment;
    }
    
    public function getFunctions()
    {
        return [
            new TwigFunction('lastMessage', [$this, 'lastMessageOfTopic']),
            new TwigFunction('gameStarNbr', [$this, 'gameStarNumber']),
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

}