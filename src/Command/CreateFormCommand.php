<?php

namespace App\Command;

use App\Entity\Form;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateFormCommand extends Command
{
    protected static $defaultName = 'form:create';

    protected EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }


    protected function configure()
    {
        $this
            ->setDescription('Creates new form');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $name = $io->ask('Form name');
        $email = $io->ask('Enter email to send submission results to');

        $form = new Form();
        $form->setEmail([$email]);
        $form->setName($name);
        $this->em->persist($form);
        $this->em->flush();

        $io->success(sprintf('Created new form [UUID: %s]', $form->getUuid()));

        return Command::SUCCESS;
    }
}
