<?php

declare(strict_types=1);

namespace Basecom\FixturePlugin;

abstract class Fixture
{
    protected FixtureHelper $helper;

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

    /**
     * @internal this method should only be called from the FixtureLoader
     */
    final public function setHelper(FixtureHelper $helper): void
    {
        $this->helper = $helper;
    }
}
