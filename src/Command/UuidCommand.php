<?php

declare(strict_types=1);

namespace Basecom\FixturePlugin\Command;

use Shopware\Core\Framework\Uuid\Uuid;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand('fixture:uuid', 'Returns a random UUID')]
class UuidCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io  = new SymfonyStyle($input, $output);

        $io->text(Uuid::randomHex());

        return Command::SUCCESS;
    }
}
