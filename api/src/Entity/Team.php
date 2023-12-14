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
use App\Dto\TeamDto;
use App\Model\InvitationInterface;
use App\Model\MemberInterface;
use App\Model\NameTrait;
use App\Model\ResourceTrait;
use App\Model\SpaceInterface;
use App\Model\TeamInterface;
use App\Model\UserInterface;
use App\Security\TeamVoter;
use App\State\CreateTeamProcessor;
use App\State\UpdateTeamProcessor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

#[API\ApiResource(
    uriTemplate: '/teams.{_format}',
    operations: [
        new API\Post(
            input: TeamDto::class,
            processor: CreateTeamProcessor::class,
        ),
    ],
    normalizationContext: [
        AbstractNormalizer::GROUPS => [self::GROUP_READ]
    ],
    denormalizationContext: [
        AbstractNormalizer::GROUPS => [self::GROUP_WRITE]
    ],
)]
#[API\ApiResource(
    uriTemplate: '/teams/{team}.{_format}',
    operations: [
        new API\Get(
            security: 'is_granted(\'' . TeamVoter::IS_USER_MEMBER . '\', object)'
        ),
        new API\Patch(
            security: 'is_granted(\'' . TeamVoter::IS_USER_OWNER . '\', object)',
            input: TeamDto::class,
            processor: UpdateTeamProcessor::class,
        ),
        new API\Delete(
            security: 'is_granted(\'' . TeamVoter::IS_USER_OWNER . '\', object)'
        ),
    ],
    uriVariables: [
        'team' => new API\Link(fromClass: self::class)
    ],
    normalizationContext: [
        AbstractNormalizer::GROUPS => [self::GROUP_READ]
    ],
    denormalizationContext: [
        AbstractNormalizer::GROUPS => [self::GROUP_WRITE]
    ],
)]
#[API\ApiResource(
    uriTemplate: '/user/teams.{_format}',
    operations: [
        new API\GetCollection(),
    ],
    normalizationContext: [
        AbstractNormalizer::GROUPS => [self::GROUP_READ]
    ],
    denormalizationContext: [
        AbstractNormalizer::GROUPS => [self::GROUP_WRITE]
    ],
)]
#[ORM\Entity]
final class Team implements TeamInterface
{
    use ResourceTrait;
    use NameTrait;

    public const GROUP_READ = 'team:read';
    public const GROUP_WRITE = 'team:write';

    /**
     * @var Collection<MemberInterface>
     */
    #[ORM\OneToMany(
        mappedBy: 'team',
        targetEntity: Member::class,
        cascade: [
            'persist',
            'remove'
        ],
        orphanRemoval: true
    )]
    private Collection $members;

    /**
     * @var Collection<InvitationInterface>
     */
    #[ORM\OneToMany(
        mappedBy: 'team',
        targetEntity: Invitation::class,
        cascade: [
            'persist',
            'remove'
        ],
        orphanRemoval: true
    )]
    private Collection $invitations;

    /**
     * @var Collection<SpaceInterface>
     */
    #[ORM\OneToMany(
        mappedBy: 'team',
        targetEntity: Space::class,
        cascade: [
            'persist',
            'remove'
        ],
        orphanRemoval: true
    )]
    private Collection $spaces;

    public function __construct(string $name, ?UuidInterface $id = null)
    {
        $this->name = $name;
        $this->id = $id ?? Uuid::uuid4();

        $this->members = new ArrayCollection();
        $this->invitations = new ArrayCollection();
        $this->spaces = new ArrayCollection();
    }

    /**
     * @return Collection<MemberInterface>
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(MemberInterface $member, bool $updateRelation = true): void
    {
        if ($this->members->contains($member)) {
            return;
        }

        $this->members->add($member);
        if ($updateRelation) {
            $member->setTeam($this, false);
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
            $invitation->setTeam($this, false);
        }
    }

    public function removeInvitation(Invitation $invitation): void
    {
        $this->invitations->removeElement($invitation);
    }

    /**
     * @return Collection<SpaceInterface>
     */
    public function getSpaces(): Collection
    {
        return $this->spaces;
    }

    public function addSpace(SpaceInterface $space, bool $updateRelation = true): void
    {
        if ($this->spaces->contains($space)) {
            return;
        }

        $this->spaces->add($space);
        if ($updateRelation) {
            $space->setTeam($this, false);
        }
    }

    public function removeSpace(SpaceInterface $space): void
    {
        $this->spaces->removeElement($space);
    }

    public function isUserMember(UserInterface $user): bool
    {
        return $this->members->exists(
            static function (int|string $key, mixed $member) use ($user) {
                /** @var MemberInterface $member */
                return $member->getUser() === $user;
            }
        );
    }

    public function isUserOwner(UserInterface $user): bool
    {
        return $this->members->exists(
            static function (int|string $key, mixed $member) use ($user) {
                /** @var MemberInterface $member */
                return $member->getUser() === $user && $member->isOwner();
            }
        );
    }
}
