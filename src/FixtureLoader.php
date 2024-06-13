<?php

declare(strict_types=1);

namespace Basecom\FixturePlugin;

use Symfony\Component\Console\Style\SymfonyStyle;

class FixtureLoader
{
    /** @var array<Fixture> */
    private readonly array $fixtures;

    /**
     * @param \Traversable<Fixture> $fixtures
     */
    public function __construct(
        \Traversable $fixtures,
        private FixtureHelper $helper,
    )
    {
        $this->fixtures = iterator_to_array($fixtures);
    }

    /**
     * This method runs the fixtures. What fixtures are executed and with what logic
     * can be configured using the FixtureOption object.
     *
     * Generally speaking the following options are available:
     * - `$dryMode`: If set to true, the fixtures will not be executed (only printed)
     * - `$groupName`: If set, only fixtures with the given group name will be executed
     * - `$fixtureNames`: If set, only fixtures with the given class name will be executed
     * - `$withDependencies`: If set to true, all dependencies of the fixtures will be executed as well
     * - `$withVendor`: If set to true, all fixtures found in vendor directory will be executed as well
     */
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

        if ($option->withDependencies) {
            $references = $this->buildFixtureReference($this->recursiveGetAllDependenciesOfFixtures($fixtures));
        }

        $this->runFixtures($option, $references, $io);

        return true;
    }

    /**
     * The prefilterFixtures method is responsible for filtering the fixtures based on the provided
     * FixtureOption object. It takes into account the group name and fixture names specified in the
     * FixtureOption. If a group name is provided, it filters the fixtures to include only those
     * belonging to the specified group. If fixture names are provided, it filters the fixtures to
     * include only those whose class names match the provided fixture names. The method returns
     * the filtered array of fixtures.
     *
     * @return array<int, Fixture>
     */
    private function prefilterFixtures(FixtureOption $option): array
    {
        $fixtures = $this->fixtures;
        $group    = $option->groupName;

        if (!empty($group)) {
            $fixtures = array_filter(
                $fixtures,
                static fn (Fixture $fixture) => \in_array(strtolower($group), array_map('strtolower', $fixture->groups()), true),
            );
        }

        if (!empty($option->fixtureNames)) {
            $fixtures = array_filter(
                $fixtures,
                static function (Fixture $fixture) use ($option) {
                    $fqcn      = $fixture::class;
                    $className = substr(strrchr($fqcn, '\\') ?: '', 1);

                    return \in_array($className, $option->fixtureNames, true);
                },
            );
        }

        if (!$option->withVendor) {
            $fixtures = array_filter(
                $fixtures,
                static function (Fixture $fixture) {
                    $reflectionClass = new \ReflectionClass($fixture::class);

                    return !str_contains($reflectionClass->getFileName() ?: '', '/vendor/');
                },
            );
        }

        return $fixtures;
    }

    /**
     * Checks that all dependencies of the fixtures are in the same group.
     *
     * This method iterates over each fixture in the provided fixture references and checks if the fixture has any dependencies.
     * If it does, it checks if these dependencies are in the same group as the fixture.
     * If not, it returns false, indicating that not all dependencies are in the same group.
     * If all dependencies are in the same group, it returns true.
     *
     * @param array<string, Fixture> $fixtureReferences
     */
    private function checkThatAllDependenciesAreInGroup(
        array $fixtureReferences,
        string $groupName,
        ?SymfonyStyle $io = null,
    ): bool {
        foreach ($fixtureReferences as $fixture) {
            if (\count($fixture->dependsOn()) <= 0) {
                continue;
            }

            if (!$this->checkDependenciesAreInSameGroup($fixture, $fixtureReferences, $groupName, $io)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if dependencies of fixture are also in the same group. If not, show error and stop process.
     *
     * @param array<string, Fixture> $references
     */
    private function checkDependenciesAreInSameGroup(
        Fixture $fixture,
        array $references,
        string $groupName,
        ?SymfonyStyle $io = null,
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
     * This method actually executed the given fixtures array. It sorts all fixtures by dependencies
     * and priority. This method will also run a correction loop to detect circular dependencies.
     *
     * If the dryMode option is set to true, the fixtures will not be executed, only printed.
     *
     * @param array<string, Fixture> $fixtures
     */
    private function runFixtures(FixtureOption $option, array $fixtures, ?SymfonyStyle $io = null): void
    {
        $io?->comment('Found '.\count($fixtures).' fixtures');

        $fixtures = $this->sortAllByPriority($fixtures);
        $fixtures = $this->buildDependencyTree($fixtures);
        $fixtures = $this->runCorrectionLoop($fixtures, 10);

        foreach ($fixtures as $fixture) {
            $io?->note('Running '.$fixture::class);

            if ($option->dryMode) {
                continue;
            }

            $fixture->setHelper($this->helper);
            $fixture->load();
        }
    }

    /**
     * Recursively retrieves all dependencies of the given fixtures.
     *
     * This method iterates over each fixture in the provided array and recursively fetches all of its dependencies.
     * It uses the `recursiveGetAllDependenciesOfFixture` method to get the dependencies of each individual fixture.
     * The result is a unique array of all dependencies for the entire set of fixtures.
     *
     * @param array<int, Fixture> $fixtures
     *
     * @return array<int, Fixture>
     */
    private function recursiveGetAllDependenciesOfFixtures(array $fixtures): array
    {
        $allFixtures = $this->buildFixtureReference($this->fixtures);

        $keys = [];
        foreach ($fixtures as $fixture) {
            $keys = array_merge($keys, $this->recursiveGetAllDependenciesOfFixture($fixture, $allFixtures));
        }

        $keys = array_unique($keys);

        return array_merge(
            $fixtures,
            array_map(
                static fn (string $key) => $allFixtures[$key],
                $keys,
            ),
        );
    }

    /**
     * Recursively retrieves all dependencies of the given fixture and returns them as an array.
     * The array contains the FQCN of all the dependency fixtures.
     *
     * @param array<string, Fixture> $allFixtures
     *
     * @return array<string>
     */
    private function recursiveGetAllDependenciesOfFixture(Fixture $fixture, array $allFixtures): array
    {
        return array_unique(array_merge($fixture->dependsOn(), array_reduce($fixture->dependsOn(), function ($carry, $item) use ($allFixtures) {
            return array_merge($carry, $this->recursiveGetAllDependenciesOfFixture($allFixtures[$item], $allFixtures));
        }, [])));
    }

    /**
     * Restructures a normal array with numeric keys to an associative array with the class name as key
     * and the fixture object as value.
     *
     * @param array<int, Fixture> $fixtures
     *
     * @return array<string, Fixture>
     */
    private function buildFixtureReference(array $fixtures): array
    {
        $result = [];

        foreach ($fixtures as $fixture) {
            $result[$fixture::class] = $fixture;
        }

        return $result;
    }

    /**
     * Sort all fixtures by priority.
     *
     * @param array<string, Fixture> $fixtures
     *
     * @return array<string, Fixture>
     */
    private function sortAllByPriority(array $fixtures): array
    {
        uasort(
            $fixtures,
            static fn (Fixture $fixture1, Fixture $fixture2): int => $fixture2->priority() <=> $fixture1->priority(),
        );

        return $fixtures;
    }

    /**
     * Sort all fixtures by dependencies. This makes sure that fixtures with dependencies are executed after their
     * dependencies.
     *
     * @param array<string, Fixture> $fixtures
     *
     * @return array<string, Fixture>
     */
    private function buildDependencyTree(array $fixtures): array
    {
        uasort($fixtures, $this->compareDependencies(...));

        return $fixtures;
    }

    /**
     * A comparison function to sort fixtures by dependencies. This function is used in the uasort function
     * to sort fixtures by dependencies.
     */
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
     * The runCorrectionLoop method is responsible for detecting circular dependencies in the fixtures.
     * It iterates over the fixtures and their dependencies and checks if there are any circular dependencies.
     * If a circular dependency is detected, it throws an exception. If no circular dependencies are detected,
     * it returns the fixtures array.
     *
     * The method takes an array of fixtures and the number of tries as arguments. The number of tries is used
     * to prevent an infinite loop in case of a circular dependency. If the number of tries reaches zero, the method
     * throws an exception. (Indicating that a circular dependency was detected)
     *
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
                if (!\array_key_exists($dependent, $fixtures)) {
                    continue;
                }

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
