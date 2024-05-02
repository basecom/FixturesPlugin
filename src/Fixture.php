<?php

declare(strict_types=1);

namespace Basecom\FixturePlugin;

abstract readonly class Fixture implements FixtureInterface
{
    abstract public function load(): void;
    
    use FixtureAwareTrait;
}
