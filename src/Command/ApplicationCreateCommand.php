<?php

namespace App\Command;

use App\Entity\Application;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'application:create', description: 'Creates new application')]
final class ApplicationCreateCommand extends Command
{
    public function __construct(protected EntityManagerInterface $em)
    {
        parent::__construct();
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
