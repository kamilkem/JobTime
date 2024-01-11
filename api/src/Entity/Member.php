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
use App\Model\MemberInterface;
use App\Model\ResourceTrait;
use App\Model\TeamInterface;
use App\Model\UserInterface;
use App\Security\TeamVoter;
use App\State\TeamSubresourceCollectionProvider;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

#[API\ApiResource(
    uriTemplate: '/members/{member}.{_format}',
    operations: [
        new API\Get(
            security: 'is_granted(\'' . TeamVoter::IS_USER_MEMBER . '\', object.getTeam())'
        ),
        new API\Patch(
            security: 'is_granted(\'' . TeamVoter::IS_USER_OWNER . '\', object.getTeam())',
        ),
        new API\Delete(
            security: 'is_granted(\'' . TeamVoter::IS_USER_OWNER . '\', object.getTeam())',
        )
    ],
    uriVariables: [
        'member' => new API\Link(fromClass: self::class),
    ],
    normalizationContext: [
        AbstractNormalizer::GROUPS => [self::GROUP_READ]
    ],
    denormalizationContext: [
        AbstractNormalizer::GROUPS => [self::GROUP_WRITE]
    ],
)]
#[API\ApiResource(
    uriTemplate: '/teams/{team}/members.{_format}',
    operations: [
        new API\GetCollection(
            provider: TeamSubresourceCollectionProvider::class,
        ),
    ],
    uriVariables: [
        'team' => new API\Link(toProperty: 'team', fromClass: Team::class)
    ],
    normalizationContext: [
        AbstractNormalizer::GROUPS => [self::GROUP_READ]
    ],
    denormalizationContext: [
        AbstractNormalizer::GROUPS => [self::GROUP_WRITE]
    ],
)]
#[ORM\Entity]
class Member implements MemberInterface
{
    use ResourceTrait;

    #[ORM\ManyToOne(targetEntity: User::class, cascade: ['persist'], inversedBy: 'members')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(groups: [self::GROUP_READ, self::GROUP_WRITE])]
    private UserInterface $user;

    #[ORM\ManyToOne(targetEntity: Team::class, cascade: ['persist'], inversedBy: 'members')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(groups: [self::GROUP_READ])]
    private TeamInterface $team;

    #[ORM\Column(type: 'boolean')]
    #[Groups(groups: [self::GROUP_READ, self::GROUP_WRITE])]
    private bool $owner;

    public const string GROUP_READ = 'member:read';
    public const string GROUP_WRITE = 'member:write';

    public function __construct(
        UserInterface $user,
        TeamInterface $team,
        bool $owner = false,
        ?UuidInterface $id = null
    ) {
        $this->user = $user;
        $this->team = $team;
        $this->owner = $owner;
        $this->id = $id ?? Uuid::uuid4();
    }

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function setUser(UserInterface $user, bool $updateRelation = true): void
    {
        $this->user = $user;

        if ($updateRelation) {
            $user->addMember($this, false);
        }
    }

    public function getTeam(): ?TeamInterface
    {
        return $this->team;
    }

    public function setTeam(TeamInterface $team, bool $updateRelation = true): void
    {
        $this->team = $team;

        if ($updateRelation) {
            $team->addMember($this, false);
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
