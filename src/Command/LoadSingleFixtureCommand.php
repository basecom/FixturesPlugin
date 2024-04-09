<?php

declare(strict_types=1);

namespace Basecom\FixturePlugin\Command;

use Basecom\FixturePlugin\FixtureLoader;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand('fixture:load:single', 'Run a single fixture')]
class LoadSingleFixtureCommand extends Command
{
    public function __construct(
        private readonly FixtureLoader $loader
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption('with-dependencies', 'w', InputOption::VALUE_NONE, 'Run fixture with dependencies')
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
