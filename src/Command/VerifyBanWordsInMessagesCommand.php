<?php

namespace App\Command;

use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class VerifyBanWordsInMessagesCommand extends Command
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var UserRepository
     */
    private $ur;

    /**
     * @var MessageRepository
     */
    private $mr;

    private $banWords = ['anal','anus','arse','ass','ballsack','balls','bastard',
    'bitch','biatch','bloody','blowjob','blow job'
    ,'bollock','bollok','boob','butt','buttplug','tongue','clitoris','cock','coon','cunt','dick','dildo',
    'fellate','fuck','homo'];
    
    protected static $defaultName = 'VerifyBanWordsInMessages';

    public function __construct(EntityManagerInterface $em, UserRepository $ur, MessageRepository $mr)
    {
        parent::__construct();
        $this->em=$em;
        $this->ur=$ur;
        $this->mr=$mr;
    }

    protected function configure(): void
    {
        $this->setDescription('verify if the messages contain ban words and ban users if they used more than 3 ban words')
        ->setHelp('nothing can help you, do it yourself')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $messages=$this->mr->getMessagesUnverified();
        foreach($messages as $message){
            foreach($this->banWords as $banword){
                if(strpos($message->getContent(),$banword)){
                    $message->getUser()->setNumberOfBanWordsUsed(($message->getUser()->getNumberOfBanWordsUsed())+1);
                }
            }
            $message->setHasBeenVerified(true);
            $this->em->persist($message);
            $this->em->persist($message->getUser());       
        }
        $this->em->flush();

        $usersToBan=$this->ur->getUsersToBan();
        if([] !== $usersToBan){
            foreach($usersToBan as $user){
                $user->setIsBan(true);
                $this->em->persist($user);
            }
            $this->em->flush();
        }
        $output->writeln(count($usersToBan).' users ban');
        return Command::SUCCESS;
    }
}



