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

use App\Entity\RefreshToken;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class RefreshTokenFixtures extends Fixture implements DependentFixtureInterface
{
    use FixtureTrait;

    private const string TOKEN = '08880aff927f921ff157d7e2571c217277a538a6db7cb47337eba188ee8e89cc7dca5f3e4c406067c2e159152c64a64814b0c2bc0941aecfcebdb0bef35a3de6';
    private const int TTL = 2592000;

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $user = $this->getFirstUser();
        $refreshToken = RefreshToken::createForUserWithTtl(self::TOKEN, $user, self::TTL);

        $manager->persist($refreshToken);
        $manager->flush();
    }
}
