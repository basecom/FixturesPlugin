<?php

declare(strict_types=1);

namespace Basecom\FixturePlugin;

use Symfony\Component\Console\Style\SymfonyStyle;
use Traversable;

class FixtureLoader
{
    private array $fixtures;
    private array $fixtureReference;

    public function __construct(Traversable $fixtures)
    {
        $this->fixtures = iterator_to_array($fixtures);
    }

    public function runAll(SymfonyStyle $io): void
    {
        $this->fixtureReference = $this->buildFixtureReference($this->fixtures);
        $this->runFixtures($io, $this->fixtures);
    }

    public function runSingle(SymfonyStyle $io, string $fixtureName, bool $withDependencies = false): void
    {
        foreach ($this->fixtures as $fixture) {
            $className = \get_class($fixture) ?: '';

            if (!str_contains(strtolower($className), strtolower($fixtureName))) {
                continue;
            }

            $io->note('Fixture '.$className.' found and will be loaded.');

            if (!$withDependencies) {
                $bag = new FixtureBag();
                $fixture->load($bag);
                return;
            }

            dd($this->recursiveGetAllDependenciesOfFixture($fixture));

            return;
        }

        $io->comment('No Fixture with name '.$fixtureName.' found');
    }

    public function runFixtureGroup(SymfonyStyle $io, string $groupName): void
    {
        $fixturesInGroup = [];

        /** @var Fixture $fixture */
        foreach ($this->fixtures as $fixture) {
            //Check if fixture has been assigned to any group, if not stop the iteration
            if (\count($fixture->groups()) <= 0) {
                continue;
            }

            foreach ($fixture->groups() as $group) {
                //Check if fixture is in affected group(from the command parameter). If not, skip the iteration.
                if (strtolower($group) !== strtolower($groupName)) {
                    continue;
                }

                $fixturesInGroup[] = $fixture;
                break;
            }
        }

        //If no fixture was found for the group, return.
        if (\count($fixturesInGroup) <= 0) {
            $io->note('No fixtures in group '.$groupName);

            return;
        }

        //Build the references, they are needed in dependency check.
        $this->fixtureReference = $this->buildFixtureReference($this->fixtures);

        foreach ($fixturesInGroup as $fixture) {
            //If fixture doesn´t has any dependencies, skip the check.
            if (\count($fixture->dependsOn()) <= 0) {
                continue;
            }

            //Check if dependencies of fixture are in the same group.
            if (!$this->checkDependenciesAreInSameGroup($io, $fixture, $groupName)) {
                return;
            }
        }

        $this->runFixtures($io, $fixturesInGroup);
    }

    /**
     * Check if dependencies of fixture are also in the same group. If not, show error and stop process.
     */
    private function checkDependenciesAreInSameGroup(SymfonyStyle $io, Fixture $fixture, string $groupName): bool
    {
        $dependencies = $fixture->dependsOn();

        foreach ($dependencies as $dependency) {
            /** @var Fixture $fixtureReference */
            $fixtureReference      = $this->fixtureReference[$dependency];
            $lowerCaseDependencies = array_map('strtolower', $fixtureReference->groups());
            if (!\in_array(strtolower($groupName), $lowerCaseDependencies, true)) {
                $io->error('Dependency '.$dependency.' of fixture '.\get_class($fixture).' is not in the same group. Please add dependant fixture '.$dependency.' to group '.$groupName);

                return false;
            }
        }

        return true;
    }

    private function runFixtures(SymfonyStyle $io, array $fixtures): void
    {
        $io->comment('Found '.\count($fixtures).' fixtures');

        $fixtures = $this->sortAllByPriority($fixtures);
        $fixtures = $this->buildDependencyTree($fixtures);
        $fixtures = $this->runCorrectionLoop($fixtures, 10);

        $bag = new FixtureBag();
        foreach ($fixtures as $fixture) {
            $io->note('Running '.\get_class($fixture));
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
            $result[\get_class($fixture)] = $fixture;
        }

        return $result;
    }

    /** @return Fixture[] */
    private function sortAllByPriority(array $fixtures): array
    {
        usort(
            $fixtures,
            static fn (Fixture $fixture1, Fixture $fixture2): int => $fixture2->priority() <=> $fixture1->priority()
        );

        return $fixtures;
    }

    /**
     * @param Fixture[] $fixtures
     *
     * @return Fixture[]
     */
    private function buildDependencyTree(array $fixtures): array
    {
        /** @var Fixture[] $sorted */
        $sorted = [];

        foreach ($fixtures as $fixture) {
            foreach ($sorted as $sort) {
                foreach ($sort->dependsOn() as $dependent) {
                    if ($dependent !== \get_class($fixture)) {
                        continue;
                    }

                    /** @var int $sortIndex */
                    $sortIndex = array_search($sort, $sorted, true);

                    array_splice($sorted, $sortIndex, 0, [$fixture]);
                    continue 3;
                }
            }

            $sorted[] = $fixture;
        }

        return $sorted;
    }

    /**
     * @param Fixture[] $fixtures
     */
    private function runCorrectionLoop(array $fixtures, int $tries): array
    {
        if ($tries <= 0) {
            throw new \LogicException('Circular dependency tree detected. Please check your dependsOn methods');
        }

        /** @var Fixture[] $existing */
        $existing = [];
        $failed   = false;

        foreach ($fixtures as $fixture) {
            if (\in_array($fixture, $existing, true)) {
                continue;
            }

            foreach ($fixture->dependsOn() as $dependent) {
                if (\in_array($this->fixtureReference[$dependent], $existing, true)) {
                    continue;
                }

                $failed     = true;
                $existing[] = $this->fixtureReference[$dependent];
            }

            $existing[] = $fixture;
        }

        if (!$failed) {
            return $existing;
        }

        return $this->runCorrectionLoop($existing, --$tries);
    }
}
