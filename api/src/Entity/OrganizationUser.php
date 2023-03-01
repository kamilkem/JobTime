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

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Model\CreatedAtTrait;
use App\Model\IdentifiableTrait;
use App\Model\OrganizationInterface;
use App\Model\OrganizationUserInterface;
use App\Model\UserInterface;
use Carbon\CarbonImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\Entity]
class OrganizationUser implements OrganizationUserInterface
{
    use IdentifiableTrait;
    use CreatedAtTrait;

    public function __construct(
        #[ORM\ManyToOne(targetEntity: User::class, cascade: ['persist'], inversedBy: 'organizationUsers')]
        private UserInterface $user,
        #[ORM\ManyToOne(targetEntity: Organization::class, cascade: ['persist'], inversedBy: 'organizationUsers')]
        private Organization $organization,
        #[ORM\Column(type: 'boolean')]
        private bool $owner
    ) {
        $this->createdAt = CarbonImmutable::now();
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function setUser(UserInterface $user, bool $updateRelation = true): void
    {
        $this->user = $user;

        if ($updateRelation) {
            $user->addOrganizationUser($this, false);
        }
    }

    public function getOrganization(): OrganizationInterface
    {
        return $this->organization;
    }

    public function setOrganization(OrganizationInterface $organization, $updateRelation = true): void
    {
        $this->organization = $organization;

        if ($updateRelation) {
            $organization->addOrganizationUser($this, false);
        }
    }

    public function isOwner(): bool
    {
        return $this->owner;
    }

    public function setOwner(bool $owner): void
    {
        $this->owner = $owner;
    }
}
