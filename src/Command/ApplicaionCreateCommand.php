<?php

namespace App\Command;

use App\Entity\Application;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ApplicaionCreateCommand extends Command
{
    protected static $defaultName = 'application:create';

    protected EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }


    protected function configure()
    {
        $this
            ->setDescription('Creates new application');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $name = $io->ask('Application name');
        $email = $io->ask('Enter email to send submissions to');

        $application = new Application();
        $application->setEmail([$email]);
        $application->setName($name);
        $this->em->persist($application);
        $this->em->flush();

        $io->success(sprintf('New application created [UUID: %s]', $application->getUuid()));

        return Command::SUCCESS;
    }
}
