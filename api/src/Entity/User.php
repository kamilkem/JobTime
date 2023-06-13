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

use ApiPlatform\Metadata as API;
use App\Model\OrganizationInvitationInterface;
use App\Model\OrganizationMemberInterface;
use App\Model\ResourceTrait;
use App\Model\UserIntegrationInterface;
use App\Model\UserInterface;
use Carbon\CarbonInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use function array_unique;
use function in_array;

#[API\ApiResource(
    operations: []
)]
#[ORM\Entity]
#[ORM\Table(name: '`user`')]
class User implements UserInterface
{
    use ResourceTrait;

    /**
     * @var Collection<OrganizationMemberInterface>
     */
    #[ORM\OneToMany(
        mappedBy: 'user',
        targetEntity: OrganizationMember::class,
        cascade: [
            'persist',
            'remove'
        ],
        orphanRemoval: true
    )]
    private Collection $organizationMembers;

    #[ORM\OneToMany(
        mappedBy: 'user',
        targetEntity: OrganizationInvitation::class,
        cascade: [
            'persist',
            'remove'
        ],
        orphanRemoval: true
    )]
    private Collection $organizationInvitations;

    /**
     * @var Collection<UserIntegrationInterface>
     */
    #[ORM\OneToMany(
        mappedBy: 'user',
        targetEntity: UserIntegration::class,
        cascade: [
            'persist',
            'remove'
        ],
        orphanRemoval: true
    )]
    private Collection $integrations;

    #[ORM\Column(nullable: true)]
    private ?string $password = null;

    private ?string $plainPassword = null;

    public function __construct(
        #[ORM\Column]
        private string $email,
        #[ORM\Column(nullable: true)]
        private ?string $firstName = null,
        #[ORM\Column(nullable: true)]
        private ?string $lastName = null,
        #[ORM\Column(type: 'carbon_immutable')]
        private ?CarbonInterface $birthDate = null,
        #[ORM\Column(type: 'boolean')]
        private bool $confirmed = false,
        #[ORM\Column(type: 'simple_array')]
        private array $roles = [],
    ) {
        if (!in_array(self::ROLE_USER, $this->roles)) {
            $this->roles[] = self::ROLE_USER;
        }

        $this->organizationMembers = new ArrayCollection();
        $this->organizationInvitations = new ArrayCollection();
        $this->integrations = new ArrayCollection();
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    public function getRoles(): array
    {
        return array_unique($this->roles);
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getBirthDate(): ?CarbonInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(?CarbonInterface $birthDate): void
    {
        $this->birthDate = $birthDate;
    }

    public function isConfirmed(): bool
    {
        return $this->confirmed;
    }

    public function setConfirmed(bool $confirmed): void
    {
        $this->confirmed = $confirmed;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * @return Collection<OrganizationMemberInterface>
     */
    public function getOrganizationMembers(): Collection
    {
        return $this->organizationMembers;
    }

    public function addOrganizationMember(OrganizationMemberInterface $organizationMember, bool $updateRelation = true): void
    {
        if ($this->organizationMembers->contains($organizationMember)) {
            return;
        }

        $this->organizationMembers->add($organizationMember);
        if ($updateRelation) {
            $organizationMember->setUser($this, false);
        }
    }

    public function removeOrganizationMember(OrganizationMemberInterface $organizationMember): void
    {
        $this->organizationMembers->removeElement($organizationMember);
    }

    /**
     * @return Collection<OrganizationInvitationInterface>
     */
    public function getOrganizationInvitations(): Collection
    {
        return $this->organizationInvitations;
    }

    public function addOrganizationInvitation(
        OrganizationInvitationInterface $organizationInvitation,
        bool $updateRelation = true
    ): void {
        if ($this->organizationInvitations->contains($organizationInvitation)) {
            return;
        }

        $this->organizationInvitations->add($organizationInvitation);
        if ($updateRelation) {
            $organizationInvitation->setUser($this, false);
        }
    }

    public function removeOrganizationInvitation(OrganizationInvitation $organizationInvitation): void
    {
        $this->organizationInvitations->removeElement($organizationInvitation);
    }

    /**
     * @return Collection<UserIntegrationInterface>
     */
    public function getIntegrations(): Collection
    {
        return $this->integrations;
    }

    public function addIntegration(UserIntegrationInterface $integration, bool $updateRelation = true): void
    {
        if ($this->integrations->contains($integration)) {
            return;
        }

        $this->integrations->add($integration);
        if ($updateRelation) {
            $integration->setUser($this, false);
        }
    }

    public function removeIntegration(UserIntegrationInterface $integration): void
    {
        $this->integrations->removeElement($integration);
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }
}
