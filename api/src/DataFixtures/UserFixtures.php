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

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use function sprintf;

final class UserFixtures extends Fixture
{
    use FixtureTrait;

    public const USER_COUNT = 10;
    public const USER_REFERENCE_NAME = 'user';

    private const DOMAIN = 'jobtime.app';
    private const PASSWORD = 'password';

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < self::USER_COUNT; $i++) {
            $user = new User(
                sprintf('%s_%d@%s', self::USER_REFERENCE_NAME, $i, self::DOMAIN),
                $this->getFaker()->firstName(),
                $this->getFaker()->lastName(),
                true,
                $this->uuid(),
            );

            $user->setPlainPassword(self::PASSWORD);

            $manager->persist($user);
            $this->addReference($this->createReferenceName(self::USER_REFERENCE_NAME, $i), $user);
        }

        $manager->flush();
    }
}
