<?php
declare(strict_types=1);

namespace Basecom\FixturePlugin\Command;


use Basecom\FixturePlugin\FixtureLoader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class LoadFixtureGroupCommand extends Command
{
    protected static $defaultName = 'fixture:load:group';

    private FixtureLoader $loader;

    public function __construct(FixtureLoader $loader)
    {
        $this->loader = $loader;

        parent::__construct(null);
    }

    protected function configure()
    {
        $this
            ->setHelp('This command allows you to run a group of fixtures')
            ->addArgument('groupName', InputArgument::REQUIRED, 'Name of fixture group');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Running a group of fixtures');

        $this->loader->runFixtureGroup($io, $input->getArgument('groupName'));

        $io->success('Done!');

        return Command::SUCCESS;
    }
}
