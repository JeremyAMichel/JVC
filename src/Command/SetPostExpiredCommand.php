<?php

namespace App\Command;

use App\Repository\PostRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SetPostExpiredCommand extends Command
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var PostRepository
     */
    private $pr;
    
    protected static $defaultName = 'SetPostExpired';

    public function __construct(EntityManagerInterface $em, PostRepository $pr)
    {
        parent::__construct();
        $this->em=$em;
        $this->pr=$pr;
    }

    protected function configure(): void
    {
        $this->setDescription('make all posts after a date given as param expired')
        ->setHelp('nothing can help you, do it yourself')
        ->addArgument('postDate', InputArgument::REQUIRED, 'a date')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $posts=$this->pr->findBeforeDate(new DateTime($input->getArgument('postDate')));
        if([] !== $posts){
            foreach($posts as $post){
                $post->setStatus(2);
                $this->em->persist($post);
            }

            $this->em->flush();

            $output->writeln(count($posts).' Posts found and updated !');

            return Command::SUCCESS;

        }else{
            $output->writeln('no Post Found');

            return Command::SUCCESS;
        }
    }
}



