<?php

declare(strict_types=1);

namespace Basecom\FixturePlugin;

use Shopware\Core\Framework\DependencyInjection\DependencyInjectionException;
use Shopware\Core\Framework\Test\TestCaseBase\IntegrationTestBehaviour;

trait FixtureTrait
{
    use IntegrationTestBehaviour;

    private function runFixtures(FixtureOption $options): void
    {
        $fixtureLoader = $this->getContainer()->get(FixtureLoader::class);

        if (!$fixtureLoader instanceof FixtureLoader) {
            throw new DependencyInjectionException(404, 'FIXTURE_LOADER_NOT_FOUND', 'Fixture Loader not found in container');
        }

        $fixtureLoader->run($options);
    }

    /**
     * @param array<string> $fixtures
     */
    private function runSpecificFixtures(array $fixtures = [], bool $withDependencies = false): void
    {
        $options = new FixtureOption(
            fixtureNames: $fixtures,
            withDependencies: $withDependencies,
        );

        $this->runFixtures($options);
    }

    private function runSingleFixture(string $fixture, bool $withDependencies = false): void
    {
        $options = new FixtureOption(
            fixtureNames: [$fixture],
            withDependencies: $withDependencies,
        );

        $this->runFixtures($options);
    }

    private function runFixtureGroup(string $group, bool $withDependencies = false): void
    {
        $options = new FixtureOption(
            groupName: $group,
            withDependencies: $withDependencies,
        );

        $this->runFixtures($options);
    }
}
