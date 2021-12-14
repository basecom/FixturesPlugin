<?php

declare(strict_types=1);

namespace spec\Basecom\FixturePlugin;

use Basecom\FixturePlugin\Fixture;
use Basecom\FixturePlugin\FixtureBag;
use Basecom\FixturePlugin\FixtureLoader;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Console\Style\SymfonyStyle;

class FixtureLoaderSpec extends ObjectBehavior
{
    public function let(): void
    {
        $this->beConstructedWith(
            new \ArrayIterator([
                new FakeFixture1(),
                new FakeFixture2(),
                new FakeFixture3(),
                new FakeFixture4(),
            ])
        );
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(FixtureLoader::class);
    }

    public function it_can_run_all_dependencies_including_priorities_and_depends_on(SymfonyStyle $io): void
    {
        $io->comment('Found 4 fixtures')->shouldBeCalledOnce();
        $io->note('Running '.FakeFixture1::class)->shouldBeCalledOnce();
        $io->note('Running '.FakeFixture2::class)->shouldBeCalledOnce();
        $io->note('Running '.FakeFixture3::class)->shouldBeCalledOnce();
        $io->note('Running '.FakeFixture4::class)->shouldBeCalledOnce();

        $this->runAll($io);

        // This ensures that the fixtures were called on the correct order
        \assert(FakeFixture1::$calledTimes === 1);
        \assert(FakeFixture4::$calledTimes === 2);
        \assert(FakeFixture3::$calledTimes === 3);
        \assert(FakeFixture2::$calledTimes === 4);
    }

    public function it_can_detect_circular_dependencies(SymfonyStyle $io): void
    {
        $this->beConstructedWith(new \ArrayIterator([new FakeFixtureCircular1(), new FakeFixtureCircular2()]));

        $io->comment('Found 2 fixtures')->shouldBeCalledOnce();

        $this->shouldThrow(
            new \LogicException('Circular dependency tree detected. Please check your dependsOn methods')
        )->during('runAll', [$io]);
    }

    public function it_can_run_single_fixture(SymfonyStyle $io): void
    {
        $fixtureName = 'FakeFixture1';
        $io->note('Fixture spec\Basecom\FixturePlugin\FakeFixture1 found and will be loaded.')->shouldBeCalledOnce();

        $this->runSingle($io, $fixtureName);
    }

    public function it_does_not_run_single_fixture_if_no_found(SymfonyStyle $io): void
    {
        $fixtureName = 'NotExistingFixture';
        $io->comment('No Fixture with name '.$fixtureName.' found')->shouldBeCalledOnce();
        $this->runSingle($io, $fixtureName);
    }

    public function it_can_run_fixtures_in_group(SymfonyStyle $io): void
    {
        $groupName = 'testGroup';

        $this->runFixtureGroup($io, $groupName);
    }

    public function it_runs_nothing_if_no_group_members_available(SymfonyStyle $io): void
    {
        $groupName = 'notExistingGroup';

        $io->note('No fixtures in group notExistingGroup')->shouldBeCalledOnce();
        $this->runFixtureGroup($io, $groupName);
    }

    public function it_detects_group_with_missing_dependency(SymfonyStyle $io): void
    {
        $groupName = 'missingDependencyGroup';

        $io->error('Dependency '.FakeFixture3::class.' of fixture '.FakeFixture2::class.' is not in the same group. Please add dependant fixture '.FakeFixture3::class.' to group '.$groupName)->shouldBeCalledOnce();
        $this->runFixtureGroup($io, $groupName);
    }

    public function it_runs_fixture_group_in_dependency_order(SymfonyStyle $io): void
    {
        $groupName = 'dependencyGroup';
        $this->runFixtureGroup($io, $groupName);
    }
}

class FakeFixture1 extends Fixture
{
    public static int $calledTimes = 0;

    public function load(FixtureBag $bag): void
    {
        $this::$calledTimes = $bag->getInt('called') ? $bag->getInt('called') + 1 : 1;
        $bag->set('called', $this::$calledTimes);
    }

    public function priority(): int
    {
        return 1;
    }

    public function groups(): array
    {
        return [
            'testGroup',
            'dependencyGroup',
        ];
    }
}

class FakeFixture2 extends Fixture
{
    public static int $calledTimes = 0;

    public function load(FixtureBag $bag): void
    {
        $this::$calledTimes = $bag->getInt('called') ? $bag->getInt('called') + 1 : 1;
        $bag->set('called', $this::$calledTimes);
    }

    public function dependsOn(): array
    {
        return [
            FakeFixture3::class,
        ];
    }

    public function groups(): array
    {
        return [
            'missingDependencyGroup',
        ];
    }
}

class FakeFixture3 extends Fixture
{
    public static int $calledTimes = 0;

    public function load(FixtureBag $bag): void
    {
        $this::$calledTimes = $bag->getInt('called') ? $bag->getInt('called') + 1 : 1;
        $bag->set('called', $this::$calledTimes);
    }

    public function priority(): int
    {
        return 10;
    }

    public function dependsOn(): array
    {
        return [FakeFixture4::class];
    }
}

class FakeFixture4 extends Fixture
{
    public static int $calledTimes = 0;

    public function load(FixtureBag $bag): void
    {
        $this::$calledTimes = $bag->getInt('called') ? $bag->getInt('called') + 1 : 1;
        $bag->set('called', $this::$calledTimes);
    }

    public function priority(): int
    {
        return -4;
    }

    public function dependsOn(): array
    {
        return [FakeFixture1::class];
    }

    public function groups(): array
    {
        return ['dependencyGroup'];
    }
}

class FakeFixtureCircular1 extends Fixture
{
    public function load(FixtureBag $bag): void
    {
    }

    public function dependsOn(): array
    {
        return [FakeFixtureCircular2::class];
    }
}

class FakeFixtureCircular2 extends Fixture
{
    public function load(FixtureBag $bag): void
    {
    }

    public function dependsOn(): array
    {
        return [FakeFixtureCircular1::class];
    }
}
