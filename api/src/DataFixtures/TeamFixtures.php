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

use App\Entity\Team;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class TeamFixtures extends Fixture
{
    use FixtureTrait;

    public const string REFERENCE_NAME = 'team';

    private const string TEAM_NAME = 'JobTime';

    public function load(ObjectManager $manager): void
    {
        $team = new Team(self::TEAM_NAME, $this->uuid());

        $manager->persist($team);
        $this->addReference(self::REFERENCE_NAME, $team);

        $manager->flush();
    }
}
