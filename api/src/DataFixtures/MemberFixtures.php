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

use App\Entity\Member;
use App\Model\TeamInterface;
use App\Model\UserInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class MemberFixtures extends Fixture implements DependentFixtureInterface
{
    use FixtureTrait;

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            TeamFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        /** @var TeamInterface $team */
        $team = $this->getReference(TeamFixtures::REFERENCE_NAME);

        for ($i = 0; $i < UserFixtures::COUNT; $i++) {
            /** @var UserInterface $user */
            $user = $this->getReference($this->createReferenceName(UserFixtures::REFERENCE_NAME, $i));

            $member = new Member($user, $team, false, $this->uuid());
            $user->addMember($member, false);
            $team->addMember($member, false);

            if (0 === $i) {
                $member->setOwner(true);
            }

            $manager->persist($member);
            $manager->flush();
        }
    }
}
