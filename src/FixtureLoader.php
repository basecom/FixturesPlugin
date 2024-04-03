<?php

declare(strict_types=1);

namespace Basecom\FixturePlugin;

use Symfony\Component\Console\Style\SymfonyStyle;

class FixtureLoader
{
    private readonly array $fixtures;
    private array $fixtureReference;

    public function __construct(\Traversable $fixtures)
    {
        $this->fixtures = iterator_to_array($fixtures);
    }

    public function run(FixtureOption $option, ?SymfonyStyle $io = null): bool
    {
        $fixtures = $this->prefilterFixtures($option);
        if (\count($fixtures) <= 0) {
            $io?->note('No fixtures found!');

            return true;
        }

        $references = $this->buildFixtureReference($fixtures);
        if (!empty($option->groupName) && !$this->checkThatAllDependenciesAreInGroup($references, $option->groupName, $io)) {
            return false;
        }

        $this->runFixtures($option, $references, $io);

        return true;
    }

    private function prefilterFixtures(FixtureOption $option): array
    {
        $fixtures = $this->fixtures;
        $group    = $option->groupName;

        if (!empty($group)) {
            $fixtures = array_filter(
                $fixtures,
                static fn (Fixture $fixture) => \in_array(strtolower($group), array_map('strtolower', $fixture->groups()), true)
            );
        }

        return $fixtures;
    }

    public function runAll(SymfonyStyle $io): void
    {
        $this->fixtureReference = $this->buildFixtureReference($this->fixtures);
        $this->runFixtures(new FixtureOption(), $this->fixtures, $io);
    }

    public function runSingle(SymfonyStyle $io, string $fixtureName, bool $withDependencies = false): void
    {
        foreach ($this->fixtures as $fixture) {
            $className = $fixture::class ?: '';

            if (!str_contains(strtolower($className), strtolower($fixtureName))) {
                continue;
            }

            $io->note('Fixture '.$className.' found and will be loaded.');

            if (!$withDependencies) {
                $bag = new FixtureBag();
                $fixture->load($bag);

                return;
            }

            $this->fixtureReference = $this->buildFixtureReference($this->fixtures);
            $this->runFixtures(new FixtureOption(), array_merge(array_map(
                fn (string $fixtureClass) => $this->fixtureReference[$fixtureClass],
                $this->recursiveGetAllDependenciesOfFixture($fixture)
            ), [$fixture]), $io);

            return;
        }

        $io->comment('No Fixture with name '.$fixtureName.' found');
    }

    /**
     * @param array<string, Fixture> $fixtureReferences
     */
    private function checkThatAllDependenciesAreInGroup(
        array $fixtureReferences,
        string $groupName,
        ?SymfonyStyle $io = null
    ): bool {
        foreach ($fixtureReferences as $fixture) {
            // If fixture doesn't have any dependencies, skip the check.
            if (\count($fixture->dependsOn()) <= 0) {
                continue;
            }

            // Check if dependencies of fixture are in the same group.
            if (!$this->checkDependenciesAreInSameGroup($fixture, $fixtureReferences, $groupName, $io)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if dependencies of fixture are also in the same group. If not, show error and stop process.
     */
    private function checkDependenciesAreInSameGroup(
        Fixture $fixture,
        array $references,
        string $groupName,
        ?SymfonyStyle $io = null
    ): bool {
        $dependencies = $fixture->dependsOn();
        $inGroup      = array_map('strtolower', array_keys($references));

        foreach ($dependencies as $dependency) {
            if (!\in_array(strtolower($dependency), $inGroup, true)) {
                $io?->error(sprintf("Dependency '%s' of fixture '%s' is not in group '%s'", $dependency, $fixture::class, $groupName));

                return false;
            }
        }

        return true;
    }

    /**
     * @param array<string, Fixture> $fixtures
     */
    private function runFixtures(FixtureOption $option, array $fixtures, ?SymfonyStyle $io = null): void
    {
        $io?->comment('Found '.\count($fixtures).' fixtures');

        $fixtures = $this->sortAllByPriority($fixtures);
        $fixtures = $this->buildDependencyTree($fixtures);
        $fixtures = $this->runCorrectionLoop($fixtures, 10);

        $bag = new FixtureBag();
        foreach ($fixtures as $fixture) {
            $io?->note('Running '.$fixture::class);

            if ($option->dryMode) {
                continue;
            }

            $fixture->load($bag);
        }
    }

    private function recursiveGetAllDependenciesOfFixture(Fixture $fixture): array
    {
        return array_unique(array_merge($fixture->dependsOn(), array_reduce($fixture->dependsOn(), function ($carry, $item) {
            return array_merge($carry, $this->recursiveGetAllDependenciesOfFixture($this->fixtureReference[$item]));
        }, [])));
    }

    private function buildFixtureReference(array $fixtures): array
    {
        $result = [];

        foreach ($fixtures as $fixture) {
            $result[$fixture::class] = $fixture;
        }

        return $result;
    }

    /**
     * @param array<string, Fixture> $fixtures
     *
     * @return array<string, Fixture>
     */
    private function sortAllByPriority(array $fixtures): array
    {
        uasort(
            $fixtures,
            static fn (Fixture $fixture1, Fixture $fixture2): int => $fixture2->priority() <=> $fixture1->priority()
        );

        return $fixtures;
    }

    /**
     * @param array<string, Fixture> $fixtures
     *
     * @return array<string, Fixture>
     */
    private function buildDependencyTree(array $fixtures): array
    {
        uasort(
            $fixtures,
            fn (Fixture $a, Fixture $b) => $this->compareDependencies($a, $b)
        );

        return $fixtures;
    }

    private function compareDependencies(Fixture $a, Fixture $b): int
    {
        $aDependsOnB = \in_array($b::class, $a->dependsOn(), true);
        $bDependsOnA = \in_array($a::class, $b->dependsOn(), true);

        if ($aDependsOnB && $bDependsOnA) {
            return -1;
        }

        return $bDependsOnA ? 1 : 0;
    }

    /**
     * @param array<string, Fixture> $fixtures
     *
     * @return array<string, Fixture>
     */
    private function runCorrectionLoop(array $fixtures, int $tries): array
    {
        if ($tries <= 0) {
            throw new \LogicException('Circular dependency tree detected. Please check your dependsOn methods');
        }

        /** @var array<string, Fixture> $existing */
        $existing = [];
        $failed   = false;

        foreach ($fixtures as $fixture) {
            if (\in_array($fixture, $existing, true)) {
                continue;
            }

            foreach ($fixture->dependsOn() as $dependent) {
                if (\in_array($fixtures[$dependent], $existing, true)) {
                    continue;
                }

                $failed               = true;
                $existing[$dependent] = $fixtures[$dependent];
            }

            $existing[$fixture::class] = $fixture;
        }

        if (!$failed) {
            return $existing;
        }

        return $this->runCorrectionLoop($existing, --$tries);
    }
}
