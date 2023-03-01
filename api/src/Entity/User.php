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
use App\Model\OrganizationUserInterface;
use App\Model\UserInterface;
use Carbon\CarbonInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use function array_unique;
use function in_array;

#[ApiResource]
#[ORM\Entity]
#[ORM\Table(name: '`user`')]
class User implements UserInterface
{
    use IdentifiableTrait;
    use CreatedAtTrait;

    /**
     * @var Collection<OrganizationUserInterface>
     */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: OrganizationUser::class)]
    private Collection $organizationUsers;

    private ?string $plainPassword = null;

    public function __construct(
        #[ORM\Column]
        private string $email,
        #[ORM\Column(nullable: true)]
        private ?string $password = null,
        #[ORM\Column(type: 'simple_array')]
        private array $roles = [],
        #[ORM\Column(nullable: true)]
        private ?string $firstName = null,
        #[ORM\Column(nullable: true)]
        private ?string $lastName = null,
        #[ORM\Column(type: 'carbon_immutable')]
        private ?CarbonInterface $birthDate = null,
        #[ORM\Column(type: 'boolean')]
        private bool $confirmed = false,
    ) {
        if (!in_array(self::ROLE_USER, $this->roles)) {
            $this->roles[] = self::ROLE_USER;
        }

        $this->organizationUsers = new ArrayCollection();
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

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }
}
