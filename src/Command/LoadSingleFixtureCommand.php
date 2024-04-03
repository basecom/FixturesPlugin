<?php

declare(strict_types=1);

namespace Basecom\FixturePlugin\Command;

use Basecom\FixturePlugin\FixtureLoader;
use Basecom\FixturePlugin\FixtureOption;
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
            ->addOption('dry', description: 'Only list fixtures that would run without executing them')
            ->addArgument('fixtureName', InputArgument::REQUIRED, 'Name of Fixture to load');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        /** @var string $fixtureName */
        $fixtureName      = $input->getArgument('fixtureName');
        $dry              = (bool) ($input->getOption('dry') ?? false);
        $withDependencies = (bool) ($input->getOption('with-dependencies') ?? false);

        if (!\is_string($fixtureName)) {
            $io->error('Please make sure that your argument is of type string');

            return Command::FAILURE;
        }

        $io->title("Running single fixture: {$fixtureName}");

        if ($dry) {
            $io->note('[INFO] Dry run mode enabled. No fixtures will be executed.');
        }

        $options = new FixtureOption(
            dryMode: $dry,
            fixtureNames: [$fixtureName],
            withDependencies: $withDependencies
        );

        if (!$this->loader->run($options, $io)) {
            return Command::FAILURE;
        }

        $io->success('Done!');

        return Command::SUCCESS;
    }
}
