<?php

namespace App\Command;

use App\Repository\ApplicationRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ApplicationListCommand extends Command
{
    protected static $defaultName = 'application:list';

    protected ApplicationRepository $repository;

    public function __construct(ApplicationRepository $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }


    protected function configure()
    {
        $this
            ->setDescription('List existing applications');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

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
