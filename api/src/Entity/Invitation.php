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
use App\Model\InvitationInterface;
use App\Model\InvitationStatusEnum;
use App\Model\TeamInterface;
use App\Model\UserInterface;
use App\Model\UserResourceTrait;
use App\Security\TeamVoter;
use App\State\TeamSubresourceCollectionProvider;
use Carbon\CarbonInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Validator\Constraints as Assert;

#[API\ApiResource(
    uriTemplate: '/invitations/{invitation}.{_format}',
    operations: [
        new API\Get(
            security: 'is_granted(\'' . TeamVoter::IS_USER_OWNER . '\', object.getTeam())',
        ),
        new API\Delete(
            security: 'is_granted(\'' . TeamVoter::IS_USER_OWNER . '\', object.getTeam())',
        )
    ],
    uriVariables: [
        'invitation' => new API\Link(fromClass: self::class),
    ],
    normalizationContext: [
        AbstractNormalizer::GROUPS => [self::GROUP_READ]
    ],
    denormalizationContext: [
        AbstractNormalizer::GROUPS => [self::GROUP_WRITE]
    ],
)]
#[API\ApiResource(
    uriTemplate: '/teams/{team}/invitations.{_format}',
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
class Invitation implements InvitationInterface
{
    use UserResourceTrait;

    public const string GROUP_READ = 'invitation:read';
    public const string GROUP_WRITE = 'invitation:write';

    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank]
    private string $invitationEmail;

    #[ORM\ManyToOne(targetEntity: Team::class, cascade: ['persist'], inversedBy: 'invitations')]
    #[Assert\NotNull]
    private TeamInterface $team;

    #[ORM\ManyToOne(targetEntity: User::class, cascade: ['persist'], inversedBy: 'invitations')]
    #[ORM\JoinColumn(nullable: true)]
    private ?UserInterface $user = null;

    #[ORM\Column(type: 'carbondatetime_immutable', nullable: true)]
    #[Groups(groups: [self::GROUP_READ])]
    private ?CarbonInterface $acceptedAt = null;

    #[ORM\Column(type: 'carbondatetime_immutable', nullable: true)]
    #[Groups(groups: [self::GROUP_READ])]
    private ?CarbonInterface $canceledAt = null;

    public function __construct()
    {
    }

    #[Groups(groups: [self::GROUP_READ])]
    public function getStatus(): InvitationStatusEnum
    {
        if ($this->acceptedAt) {
            return InvitationStatusEnum::ACCEPTED;
        }

        if ($this->canceledAt) {
            return InvitationStatusEnum::CANCELLED;
        }

        return InvitationStatusEnum::PENDING;
    }

    public function getInvitationEmail(): string
    {
        return $this->invitationEmail;
    }

    public function setInvitationEmail(string $invitationEmail): void
    {
        $this->invitationEmail = $invitationEmail;
    }

    public function getTeam(): TeamInterface
    {
        return $this->team;
    }

    public function setTeam(TeamInterface $team, bool $updateRelation = true): void
    {
        $this->team = $team;

        if ($updateRelation) {
            $team->addInvitation($this, false);
        }
    }

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function setUser(?UserInterface $user, bool $updateRelation = true): void
    {
        $this->user = $user;

        if ($user && $updateRelation) {
            $user->addInvitation($this, false);
        }
    }

    public function getAcceptedAt(): ?CarbonInterface
    {
        return $this->acceptedAt;
    }

    public function setAcceptedAt(?CarbonInterface $acceptedAt): void
    {
        if ($this->canceledAt) {
            throw new \LogicException();
        }

        $this->acceptedAt = $acceptedAt;
    }

    public function getCanceledAt(): ?CarbonInterface
    {
        if ($this->acceptedAt) {
            throw new \LogicException();
        }

        return $this->canceledAt;
    }

    public function setCanceledAt(?CarbonInterface $canceledAt): void
    {
        $this->canceledAt = $canceledAt;
    }
}
