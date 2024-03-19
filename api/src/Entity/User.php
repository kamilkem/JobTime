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
use App\Dto\UserInput;
use App\Model\InvitationInterface;
use App\Model\MemberInterface;
use App\Model\ResourceTrait;
use App\Model\UserInterface;
use App\Security\UserVoter;
use App\State\UpdateUserProcessor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

use function array_unique;
use function in_array;

#[API\ApiResource(
    uriTemplate: '/users/{user}.{_format}',
    operations: [
        new API\Get(),
        new API\Patch(
            security: 'is_granted(\'' . UserVoter::IS_USER_INSTANCE . '\', object)',
            input: UserInput::class,
            processor: UpdateUserProcessor::class,
        ),
    //        new API\Delete(
    //            security: 'is_granted(\'' . UserVoter::IS_USER_INSTANCE . '\', object)',
    //        ),
    ],
    uriVariables: [
        'user' => new API\Link(fromClass: self::class),
    ],
    normalizationContext: [
        AbstractNormalizer::GROUPS => self::AGGREGATE_READ_GROUPS,
    ],
    denormalizationContext: [
        AbstractNormalizer::GROUPS => self::AGGREGATE_WRITE_GROUPS,
    ],
)]
#[ORM\Entity]
#[ORM\Table(name: '`user`')]
class User implements UserInterface
{
    use ResourceTrait;

    #[ORM\Column(type: 'string')]
    #[Groups([self::GROUP_READ])]
    private string $email;

    #[ORM\Column(type: 'string')]
    #[Groups([self::GROUP_READ, self::GROUP_WRITE])]
    private string $firstName;

    #[ORM\Column(type: 'string')]
    #[Groups([self::GROUP_READ, self::GROUP_WRITE])]
    private string $lastName;

    #[ORM\Column(type: 'boolean')]
    private bool $confirmed;

    #[ORM\Column(type: 'simple_array')]
    private array $roles = [];

    /**
     * @var Collection<MemberInterface>
     */
    #[ORM\OneToMany(
        mappedBy: 'user',
        targetEntity: Member::class,
        cascade: [
            'persist',
            'remove'
        ],
        orphanRemoval: true
    )]
    private Collection $members;

    #[ORM\OneToMany(
        mappedBy: 'user',
        targetEntity: Invitation::class,
        cascade: [
            'persist',
            'remove'
        ],
        orphanRemoval: true
    )]
    private Collection $invitations;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $password = null;

    private ?string $plainPassword = null;

    public function __construct(
        string $email,
        string $firstName,
        string $lastName,
        bool $confirmed = false,
        ?UuidInterface $id = null
    ) {
        $this->email = $email;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->confirmed = $confirmed;
        $this->id = $id ?? Uuid::uuid4();

        if (!in_array(self::ROLE_USER, $this->roles)) {
            $this->roles[] = self::ROLE_USER;
        }

        $this->members = new ArrayCollection();
        $this->invitations = new ArrayCollection();
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

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
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
     * @return Collection<MemberInterface>
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(
        MemberInterface $member,
        bool $updateRelation = true
    ): void {
        if ($this->members->contains($member)) {
            return;
        }

        $this->members->add($member);
        if ($updateRelation) {
            $member->setUser($this, false);
        }
    }

    public function removeMember(MemberInterface $member): void
    {
        $this->members->removeElement($member);
    }

    /**
     * @return Collection<InvitationInterface>
     */
    public function getInvitations(): Collection
    {
        return $this->invitations;
    }

    public function addInvitation(
        InvitationInterface $invitation,
        bool $updateRelation = true
    ): void {
        if ($this->invitations->contains($invitation)) {
            return;
        }

        $this->invitations->add($invitation);
        if ($updateRelation) {
            $invitation->setUser($this, false);
        }
    }

    public function removeInvitation(Invitation $invitation): void
    {
        $this->invitations->removeElement($invitation);
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
