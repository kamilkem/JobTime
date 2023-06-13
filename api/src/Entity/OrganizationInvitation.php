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
use App\Dto\CreateOrganizationInvitationInput;
use App\Model\OrganizationInterface;
use App\Model\OrganizationInvitationInterface;
use App\Model\OrganizationInvitationStatusEnum;
use App\Model\UserInterface;
use App\Model\UserResourceTrait;
use App\Security\OrganizationVoter;
use App\State\CreateOrganizationInvitationProcessor;
use App\State\OrganizationSubresourceCollectionProvider;
use Carbon\CarbonInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Validator\Constraints as Assert;

#[API\ApiResource(
    uriTemplate: '/invitations/{organization}/{invitation}.{_format}',
    operations: [
        new API\Get(
            security: 'is_granted(\'' . OrganizationVoter::IS_USER_OWNER . '\', object.getOrganization())',
        ),
        new API\Delete(
            security: 'is_granted(\'' . OrganizationVoter::IS_USER_OWNER . '\', object.getOrganization())',
        )
    ],
    uriVariables: [
        'organization' => new API\Link(toProperty: 'organization', fromClass: Organization::class),
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
    uriTemplate: '/orgs/{organization}/invitations.{_format}',
    operations: [
        new API\GetCollection(
            provider: OrganizationSubresourceCollectionProvider::class,
        ),
        new API\Post(
            input: CreateOrganizationInvitationInput::class,
            read: false,
            processor: CreateOrganizationInvitationProcessor::class,
        )
    ],
    uriVariables: [
        'organization' => new API\Link(toProperty: 'organization', fromClass: Organization::class)
    ],
    normalizationContext: [
        AbstractNormalizer::GROUPS => [self::GROUP_READ]
    ],
    denormalizationContext: [
        AbstractNormalizer::GROUPS => [self::GROUP_WRITE]
    ],
)]
#[ORM\Entity]
class OrganizationInvitation implements OrganizationInvitationInterface
{
    use UserResourceTrait;

    public const GROUP_READ = 'organization_invitation:read';
    public const GROUP_WRITE = 'organization_invitation:write';

    #[ORM\Column(type: 'carbon_immutable', nullable: true)]
    #[Groups(groups: [self::GROUP_READ])]
    private ?CarbonInterface $acceptedAt = null;

    #[ORM\Column(type: 'carbon_immutable', nullable: true)]
    #[Groups(groups: [self::GROUP_READ])]
    private ?CarbonInterface $canceledAt = null;

    public function __construct(
        #[ORM\Column]
        #[Assert\NotBlank]
        private ?string $invitationEmail = null,
        #[ORM\ManyToOne(targetEntity: Organization::class, cascade: ['persist'], inversedBy: 'invitations')]
        #[Assert\NotNull]
        private ?OrganizationInterface $organization = null,
        #[ORM\ManyToOne(targetEntity: User::class, cascade: ['persist'], inversedBy: 'organizationInvitations')]
        private ?UserInterface $user = null,
    ) {
    }

    #[Groups(groups: [self::GROUP_READ])]
    public function getStatus(): OrganizationInvitationStatusEnum
    {
        if ($this->acceptedAt) {
            return OrganizationInvitationStatusEnum::ACCEPTED;
        }

        if ($this->canceledAt) {
            return OrganizationInvitationStatusEnum::CANCELLED;
        }

        return OrganizationInvitationStatusEnum::PENDING;
    }

    public function getInvitationEmail(): ?string
    {
        return $this->invitationEmail;
    }

    public function setInvitationEmail(?string $invitationEmail): void
    {
        $this->invitationEmail = $invitationEmail;
    }

    public function getOrganization(): ?OrganizationInterface
    {
        return $this->organization;
    }

    public function setOrganization(?OrganizationInterface $organization, bool $updateRelation = true): void
    {
        $this->organization = $organization;

        if ($organization && $updateRelation) {
            $organization->addInvitation($this, false);
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
            $user->addOrganizationInvitation($this, false);
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
