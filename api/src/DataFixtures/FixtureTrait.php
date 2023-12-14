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

namespace App\DataFixtures;

use App\DataFixtures\Faker\FakerTrait;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

trait FixtureTrait
{
    use FakerTrait;

    public function createReferenceName(string $name, string|int $suffix, string $separator = '_'): string
    {
        if ($name[-1] === $separator) {
            $name = substr($name, 0, -1);
        }

        return sprintf('%s%s%s', $name, $separator, $suffix);
    }

    public function uuid(): UuidInterface
    {
        return Uuid::fromString($this->getFaker()->uuid());
    }
}
