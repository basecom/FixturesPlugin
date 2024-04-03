<?php

declare(strict_types=1);

namespace Basecom\FixturePlugin;

readonly class FixtureOption
{
    public function __construct(
        public bool $dryMode = false,
        public ?string $groupName = null
    ) {
    }
}
