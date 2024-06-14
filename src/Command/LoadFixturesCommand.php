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
        private readonly FixtureLoader $loader,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption('dry', description: 'Only list fixtures that would run without executing them')
            ->addOption('vendor', description: 'Include fixtures from vendor packages');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $now = new \DateTime();
        $io = new SymfonyStyle($input, $output);

        $dry    = (bool) ($input->getOption('dry') ?? false);
        $vendor = (bool) ($input->getOption('vendor') ?? false);

        $io->title('Running all fixtures');

        if ($dry) {
            $io->note('[INFO] Dry run mode enabled. No fixtures will be executed.');
        }

        if ($vendor) {
            $io->note('[INFO] Including fixtures from vendor packages.');
        }

        $option = new FixtureOption(
            dryMode: $dry,
            withVendor: $vendor,
        );

        if (!$this->loader->run($option, $io)) {
            return Command::FAILURE;
        }

        $interval = $now->diff(new \DateTime());
        $tookSeconds = $interval->s + $interval->i * 60 + $interval->h * 3600 + $interval->d * 86400 + $interval->m * 2592000 + $interval->y * 31536000;
        $io->success('Done! Took ' . $tookSeconds . 's');

        return Command::SUCCESS;
    }
}
