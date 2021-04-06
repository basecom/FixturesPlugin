<?php

declare(strict_types=1);

namespace Basecom\FixturePlugin\Command;

use Basecom\FixturePlugin\FixtureLoader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class LoadFixturesCommand extends Command
{
    protected static $defaultName = 'fixture:load';

    private FixtureLoader $loader;

    public function __construct(FixtureLoader $loader)
    {
        parent::__construct(null);

        $this->loader = $loader;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Running all fixtures');

        $this->loader->runAll($io);

        $io->success('Done!');

        return 0;
    }
}
