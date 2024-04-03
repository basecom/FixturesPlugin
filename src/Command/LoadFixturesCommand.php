<?php

declare(strict_types=1);

namespace Basecom\FixturePlugin\Command;

use Basecom\FixturePlugin\FixtureLoader;
use Basecom\FixturePlugin\FixtureOption;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand('fixture:load', 'Run all fixtures in the project')]
class LoadFixturesCommand extends Command
{
    public function __construct(
        private readonly FixtureLoader $loader
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption('dry', description: 'Only list fixtures that would run without executing them');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $dry = (bool) ($input->getOption('dry') ?? false);

        $io->title('Running all fixtures');

        if ($dry) {
            $io->note('[INFO] Dry run mode enabled. No fixtures will be executed.');
        }

        $option = new FixtureOption(
            dryMode: $dry
        );

        if (!$this->loader->run($option, $io)) {
            return Command::FAILURE;
        }

        $io->success('Done!');

        return Command::SUCCESS;
    }
}
