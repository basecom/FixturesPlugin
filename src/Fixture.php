<?php

declare(strict_types=1);

namespace Basecom\FixturePlugin;

abstract readonly class Fixture
{
    abstract public function load(): void;

    /** @return string[] */
    public function dependsOn(): array
    {
        return [];
    }

    public function priority(): int
    {
        return 0;
    }

    /** @return string[] */
    public function groups(): array
    {
        return [];
    }
}
