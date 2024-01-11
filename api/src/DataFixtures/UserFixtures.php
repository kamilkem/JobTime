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
use App\Model\UserInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use function sprintf;

final class UserFixtures extends Fixture
{
    use FixtureTrait;

    public const int COUNT = 10;
    public const string REFERENCE_NAME = 'user';
    public const string NOT_CONFIRMED_REFERENCE_NAME = 'not_confirmed_user';

    private const string DOMAIN = 'jobtime.app';
    private const string PASSWORD = 'password';

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < self::COUNT; $i++) {
            $user = $this->createUser(
                sprintf('%s_%d@%s', self::REFERENCE_NAME, $i, self::DOMAIN),
                true,
            );

            $user->setPlainPassword(self::PASSWORD);

            $manager->persist($user);
            $this->addReference($this->createReferenceName(self::REFERENCE_NAME, $i), $user);
        }

        $user = $this->createUser(
            sprintf('%s@%s', self::NOT_CONFIRMED_REFERENCE_NAME, self::DOMAIN),
            false,
        );

        $manager->persist($user);
        $this->addReference(self::NOT_CONFIRMED_REFERENCE_NAME, $user);

        $manager->flush();
    }

    private function createUser(string $email, bool $confirmed): UserInterface
    {
        return new User(
            $email,
            $this->getFaker()->firstName(),
            $this->getFaker()->lastName(),
            $confirmed,
            $this->uuid(),
        );
    }
}
