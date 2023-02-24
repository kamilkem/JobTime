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

use ApiPlatform\Metadata\ApiResource;
use App\Model\IdentifiableTrait;
use App\Model\OrganizationInterface;
use App\Model\OrganizationUserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\Entity]
class Organization implements OrganizationInterface
{
    use IdentifiableTrait;

    /**
     * @var Collection<OrganizationUserInterface>
     */
    #[ORM\OneToMany(mappedBy: 'organization', targetEntity: OrganizationUser::class)]
    private Collection $organizationUsers;

    public function __construct(
        #[ORM\Column]
        private string $name,
    ) {
        $this->organizationUsers = new ArrayCollection();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return Collection<OrganizationUserInterface>
     */
    public function getOrganizationUsers(): Collection
    {
        return $this->organizationUsers;
    }

    public function addOrganizationUser(OrganizationUserInterface $organizationUser): void
    {
        $this->organizationUsers->add($organizationUser);
    }

    public function removeOrganizationUser(OrganizationUserInterface $organizationUser): void
    {
        $this->organizationUsers->removeElement($organizationUser);
    }
}
