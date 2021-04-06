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
        $this->fixtures = \iterator_to_array($fixtures);
    }

    public function runAll(SymfonyStyle $io): void
    {
        $io->comment('Found '.\count($this->fixtures).' fixtures');

        $this->fixtureReference = $this->buildFixtureReference($this->fixtures);

        $fixtures = $this->sortAllByPriority($this->fixtures);
        $fixtures = $this->buildDependencyTree($fixtures);
        $fixtures = $this->runCorrectionLoop($fixtures, 10);

        $bag = new FixtureBag();
        foreach ($fixtures as $fixture) {
            $io->note('Running '.\get_class($fixture));
            $fixture->load($bag);
        }
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
        \usort(
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
                    $sortIndex = \array_search($sort, $sorted, true);

                    \array_splice($sorted, $sortIndex, 0, [$fixture]);
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
