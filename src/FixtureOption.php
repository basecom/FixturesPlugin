<?php

declare(strict_types=1);

namespace Basecom\FixturePlugin;

readonly class FixtureOption
{
    /**
     * @param array<string> $fixtureNames
     */
    public function __construct(
        public bool $dryMode = false,
        public ?string $groupName = null,
        public array $fixtureNames = [],
        public bool $withDependencies = false,
        public bool $withVendor = false,
    ) {
    }
}
