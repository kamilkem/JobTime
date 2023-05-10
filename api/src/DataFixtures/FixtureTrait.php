<?php

/**
 * This file is part of the JobTime package.
 *
 * (c) Kamil Kozaczyński <kozaczynski.kamil@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\DataFixtures;

trait FixtureTrait
{
    public function createReferenceName(string $name, string|int $suffix, string $separator = '_'): string
    {
        if ($name[-1] === $separator) {
            $name = substr($name, 0, -1);
        }

        return sprintf('%s%s%s', $name, $separator, $suffix);
    }
}
