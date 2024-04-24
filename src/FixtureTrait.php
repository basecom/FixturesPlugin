<?php

declare(strict_types=1);

namespace Basecom\FixturePlugin;

use Shopware\Core\Framework\Test\TestCaseBase\KernelLifecycleManager;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\ConsoleOutput;

trait FixtureTrait
{
    private function runFixtures(?array $fixtures = []): void
    {
        if (empty($fixtures)) {
            $application    = new Application(KernelLifecycleManager::getKernel());
            $fixtureCommand = $application->find('fixture:load');

            $returnCode = $fixtureCommand->run(
                new ArrayInput(
                    $fixtures,
                    $fixtureCommand->getDefinition(),
                ),
                new BufferedOutput(), // use new ConsoleOutput() if you don't want to hide output, new BufferedOutput()
            );

            if ($returnCode !== 0) {
                throw new \RuntimeException('fixture:load');
            }

            return;
        }

        foreach ($fixtures as $fixture) {
            $application = new Application(KernelLifecycleManager::getKernel());

            $fixtureCommand = $application->find('fixture:load:single');

            $returnCode = $fixtureCommand->run(
                new ArrayInput(
                    ['fixtureName' => $fixture],
                    $fixtureCommand->getDefinition(),
                ),
                new BufferedOutput(), // use new ConsoleOutput() if you don't want to hide output, new BufferedOutput()
            );
            if ($returnCode !== 0) {
                throw new \RuntimeException('fixture:single');
            }
        }
    }

    private function runSingleFixtureWithDependencies(string $fixture): void
    {
        $application = new Application(KernelLifecycleManager::getKernel());

        $fixtureCommand = $application->find('fixture:load:single');

        $returnCode = $fixtureCommand->run(
            new ArrayInput(
                ['fixtureName' => $fixture, '--with-dependencies' => true],
                $fixtureCommand->getDefinition(),
            ),
            new BufferedOutput(),
        );

        if ($returnCode !== 0) {
            throw new \RuntimeException('fixture:single');
        }
    }
}
