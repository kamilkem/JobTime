<?php

/**
 * This file is part of the jobtime-backend package.
 *
 * (c) Kamil Kozaczyński <kozaczynski.kamil@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Entity;

use App\Model\OrganizationInterface;
use App\Model\OrganizationUserInterface;
use App\Model\UserInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class OrganizationUser implements OrganizationUserInterface
{
    public function __construct(
        #[ORM\Id]
        #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'organizationUsers')]
        private UserInterface $user,
        #[ORM\Id]
        #[ORM\ManyToOne(targetEntity: Organization::class, inversedBy: 'organizationUsers')]
        private Organization $organization,
        #[ORM\Column(type: 'boolean')]
        private bool $owner
    ) {
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function setUser(UserInterface $user): void
    {
        $this->user = $user;
        $user->addOrganizationUser($this);
    }

    public function getOrganization(): OrganizationInterface
    {
        return $this->organization;
    }

    public function setOrganization(OrganizationInterface $organization): void
    {
        $this->organization = $organization;
        $organization->addOrganizationUser($this);
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
