<?php

namespace App\Command;

use App\Repository\ApplicationRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'application:list', description: 'List existing applications')]
final class ApplicationListCommand extends Command
{
    public function __construct(protected ApplicationRepository $repository)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $rows = [];

        foreach ($this->repository->findAll() as $application) {
            $rows[] = [
                $application->getUuid(),
                $application->getName(),
                implode(', ', $application->getEmail()),
            ];
        }

        $table = new Table($output);
        $table->setHeaders(['UUID', 'Name', 'Email'])
            ->setRows($rows)
            ->render();

        return Command::SUCCESS;
    }
}
