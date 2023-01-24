<?php

namespace App\Command;

use App\Entity\Category;
use App\Entity\Ticket;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use DateTime;

#[AsCommand(
    name: 'app:CreateTicket',
    description: 'Command qui crée un ticket',
)]
class CreateTicketCommand extends Command
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        parent::__construct();
    }
    protected function configure(): void
    {
        $this
            ->addArgument('Title', InputArgument::OPTIONAL, 'Titre du ticket');
//            ->addArgument('Description', InputArgument::OPTIONAL, 'Description du ticket')
//            ->addArgument('Date', InputArgument::OPTIONAL, 'Date du ticket')
    }

    /**
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $title = $input->getArgument('Title');

        if ($title) {
            $io->note(sprintf('You passed an argument: %s', $title));
        }

        $category = (new Category())
            ->setTitle(sprintf("Category n°%d", rand()));

        $this->em->persist($category);
        $this->em->flush();

        $ticket = (new Ticket())
        ->setTitle('Ticket n°'. rand())
        ->setCategory($category)
        ->setDate(new DateTime());

        if (!$ticket->getDate() instanceof DateTime){
            throw new \Exception('PAS DE DATE');
        }

        if (null === $ticket->getCategory()){
            throw new \Exception('PAS DE CATEGORY');
        }



        $this->em->persist($ticket);
        $this->em->flush();

        dd($ticket);

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
