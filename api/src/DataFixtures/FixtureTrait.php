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
use App\Model\UserInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

trait FixtureTrait
{
    use FakerTrait;

    protected function createReferenceName(string $name, string|int $suffix, string $separator = '_'): string
    {
        if ($name[-1] === $separator) {
            $name = substr($name, 0, -1);
        }

        return sprintf('%s%s%s', $name, $separator, $suffix);
    }

    protected function uuid(): UuidInterface
    {
        return Uuid::fromString($this->getFaker()->uuid());
    }

    protected function getRandomUser(): UserInterface
    {
        /** @var UserInterface $user */
        $user = $this->getReference(
            $this->createReferenceName(
                UserFixtures::REFERENCE_NAME,
                $this->getFaker()->numberBetween(0, UserFixtures::COUNT - 1)
            )
        );

        return $user;
    }

    protected function getFirstUser(): UserInterface
    {
        /** @var UserInterface $user */
        $user = $this->getReference($this->createReferenceName(UserFixtures::REFERENCE_NAME, 0));

        return $user;
    }
}
