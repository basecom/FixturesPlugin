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
