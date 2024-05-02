<?php
declare(strict_types=1);

namespace Basecom\FixturePlugin;

interface FixtureInterface
{
    public function load(): void;

    /** @return string[] */
    public function dependsOn(): array;

    public function priority(): int;

    /** @return string[] */
    public function groups(): array;
}