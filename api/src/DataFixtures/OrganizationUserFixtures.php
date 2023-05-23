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

use App\Entity\OrganizationUser;
use App\Model\OrganizationInterface;
use App\Model\UserInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class OrganizationUserFixtures extends Fixture implements DependentFixtureInterface
{
    use FixtureTrait;

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            OrganizationFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        /** @var OrganizationInterface $organization */
        $organization = $this->getReference(OrganizationFixtures::ORGANIZATION_REFERENCE_NAME);

        for ($i = 0; $i < UserFixtures::USER_COUNT; $i++) {
            /** @var UserInterface $user */
            $user = $this->getReference($this->createReferenceName(UserFixtures::USER_REFERENCE_NAME, $i));

            $organizationUser = new OrganizationUser(
                $user,
                $organization,
                0 === $i
            );

            $manager->persist($organizationUser);
            $manager->flush();
        }
    }
}
