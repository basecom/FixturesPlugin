<?php

declare(strict_types=1);

namespace Basecom\FixturePlugin;

class DurationCalculator
{
    public const SECONDS_IN_A_MINUTE = 60;
    public const SECONDS_IN_A_HOUR   = self::SECONDS_IN_A_MINUTE * 60;

    public static function calculateSeconds(\DateTimeImmutable $a, \DateTimeImmutable $b): int
    {
        $difference    = $a->diff($b);

        return $difference->s + $difference->i * self::SECONDS_IN_A_MINUTE + $difference->h * self::SECONDS_IN_A_HOUR;
    }
}
