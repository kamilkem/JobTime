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

use App\Entity\Organization;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class OrganizationFixtures extends Fixture
{
    public const ORGANIZATION_REFERENCE_NAME = 'organization';

    private const ORGANIZATION_NAME = 'JobTime';

    public function load(ObjectManager $manager): void
    {
        $organization = new Organization(
            self::ORGANIZATION_NAME,
        );

        $manager->persist($organization);
        $this->addReference(self::ORGANIZATION_REFERENCE_NAME, $organization);

        $manager->flush();
    }
}
