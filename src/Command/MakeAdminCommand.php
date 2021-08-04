<?php

namespace App\Command;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MakeAdminCommand extends Command
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var UserRepository
     */
    private $ur;
    
    protected static $defaultName = 'MakeAdmin';

    public function __construct(EntityManagerInterface $em, UserRepository $ur)
    {
        parent::__construct();
        $this->em=$em;
        $this->ur=$ur;
    }

    protected function configure(): void
    {
        $this->setDescription('Make an user ADMIN using his mail')
        ->setHelp('nothing can help you, do it yourself')
        ->addArgument('userMail', InputArgument::REQUIRED, 'the user mail')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $user=$this->ur->findOneBy(['email'=>$input->getArgument('userMail')]);
        if(null !== $user){

            $user->setRoles(["ROLE_ADMIN"]);

            
            $this->em->persist($user);
            $this->em->flush();

            $output->writeln('User '.$input->getArgument('userMail').' updated !');

            return Command::SUCCESS;

        }else{
            $output->writeln('User not found');

            return Command::SUCCESS;
        }
    }
}



