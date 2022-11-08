<?php

declare(strict_types=1);

namespace Basecom\FixturePlugin\Command;

use Basecom\FixturePlugin\FixtureLoader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class LoadSingleFixtureCommand extends Command
{
    protected static $defaultName = 'fixture:load:single';

    private FixtureLoader $loader;

    public function __construct(FixtureLoader $loader)
    {
        $this->loader = $loader;
        parent::__construct(null);
    }

    protected function configure(): void
    {
        $this
            ->setHelp('This command allows you to run only one specific fixture')
            ->addOption('with-dependencies', 'w', InputOption::VALUE_NONE, 'Run fixture with dependencies')
            ->addArgument('fixtureName', InputArgument::REQUIRED, 'Name of Fixture to load');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Running a single fixture');

        $groupNameInput = $input->getArgument('fixtureName');

        if (!\is_string($groupNameInput)) {
            $io->error('Please make sure that your argument is of type string');

            return Command::FAILURE;
        }

        $withDependencies = $input->getOption('with-dependencies');
        if (!\is_bool($withDependencies)) {
            $io->error('Please make sure that your argument is of type boolean');

            return Command::FAILURE;
        }

        $this->loader->runSingle($io, $groupNameInput, $withDependencies);

        $io->success('Done!');

        return Command::SUCCESS;
    }
}
