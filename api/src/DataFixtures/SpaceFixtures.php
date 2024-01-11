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

use App\Entity\Space;
use App\Model\TeamInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class SpaceFixtures extends Fixture implements DependentFixtureInterface
{
    use FixtureTrait;

    public const int COUNT = 3;
    public const string REFERENCE_NAME = 'space';

    public function getDependencies(): array
    {
        return [
            TeamFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        /** @var TeamInterface $team */
        $team = $this->getReference(TeamFixtures::REFERENCE_NAME);

        for ($i = 0; $i < self::COUNT; $i++) {
            $space = new Space(
                $team,
                $this->getFaker()->word(),
                $this->getFaker()->sentence(),
                $this->uuid()
            );

            $space->setCreatedBy($this->getRandomUser());

            $manager->persist($space);
            $this->addReference($this->createReferenceName(self::REFERENCE_NAME, $i), $space);
        }

        $manager->flush();
    }
}
