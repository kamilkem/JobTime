<?php

/**
 * This file is part of the JobTime package.
 *
 * (c) Kamil Kozaczyński <kozaczynski.kamil@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\DataFixtures\Faker;

use Faker\Factory;
use Faker\Generator;

final class SeededFakerFactory
{
    /** @var Generator[] */
    private static array $fakers = [];

    /** @var int[] */
    private static array $seeds = [];

    public static function create(string $name): Generator
    {
        if (!isset(self::$fakers[$name])) {
            $seed = self::generateFakerSeed($name);
            self::checkSeedIsUnique($seed, $name);
            self::$seeds[$name] = $seed;

            $faker = Factory::create();
            $faker->seed($seed);
            self::$fakers[$name] = $faker;
        }

        return self::$fakers[$name];
    }

    public static function refresh(): void
    {
        self::$fakers = [];
        self::$seeds = [];
    }

    private static function generateFakerSeed(string $name): int
    {
        $ord     = (array) unpack('C*', $name);
        $reduced = array_reduce(
            $ord,
            static function (int $a, int $b) {
                return $a + $b;
            },
            0
        );

        return count($ord) + $reduced;
    }

    private static function checkSeedIsUnique(int $seed, string $name): void
    {
        $key = array_search($seed, self::$seeds, true);

        if (false !== $key && $key !== $name) {
            throw new \RuntimeException(
                sprintf('Seed %s already exists for name "%s", other than "%s".', $seed, $key, $name)
            );
        }
    }

    private function __construct()
    {
    }
}
