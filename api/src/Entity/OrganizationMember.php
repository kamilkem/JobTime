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
use App\Dto\CreateOrganizationMemberInput;
use App\Model\OrganizationInterface;
use App\Model\OrganizationMemberInterface;
use App\Model\ResourceTrait;
use App\Model\UserInterface;
use App\Security\OrganizationVoter;
use App\State\CreateOrganizationMemberProcessor;
use App\State\OrganizationSubresourceCollectionProvider;
use Carbon\CarbonImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Validator\Constraints as Assert;

#[API\ApiResource(
    uriTemplate: '/members/{organization}/{member}.{_format}',
    operations: [
        new API\Get(
            security: 'is_granted(\'' . OrganizationVoter::IS_USER_MEMBER . '\', object.getOrganization())'
        ),
        new API\Patch(
            security: 'is_granted(\'' . OrganizationVoter::IS_USER_OWNER . '\', object.getOrganization())',
        ),
        new API\Delete(
            security: 'is_granted(\'' . OrganizationVoter::IS_USER_OWNER . '\', object.getOrganization())',
        )
    ],
    uriVariables: [
        'organization' => new API\Link(toProperty: 'organization', fromClass: Organization::class),
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
    uriTemplate: '/orgs/{organization}/members.{_format}',
    operations: [
        new API\GetCollection(
            provider: OrganizationSubresourceCollectionProvider::class,
        ),
        new API\Post(
            input: CreateOrganizationMemberInput::class,
            read: false,
            processor: CreateOrganizationMemberProcessor::class,
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
class OrganizationMember implements OrganizationMemberInterface
{
    use ResourceTrait;

    public const GROUP_READ = 'organization_user:read';
    public const GROUP_WRITE = 'organization_user:write';

    public function __construct(
        #[ORM\ManyToOne(targetEntity: User::class, cascade: ['persist'], inversedBy: 'organizationMembers')]
        #[Assert\NotNull]
        #[Groups(groups: [self::GROUP_READ, self::GROUP_WRITE])]
        private ?UserInterface $user = null,
        #[ORM\ManyToOne(targetEntity: Organization::class, cascade: ['persist'], inversedBy: 'members')]
        #[Groups(groups: [self::GROUP_READ])]
        private ?OrganizationInterface $organization = null,
        #[ORM\Column(type: 'boolean')]
        #[Groups(groups: [self::GROUP_READ, self::GROUP_WRITE])]
        private bool $owner = false,
    ) {
        $this->createdAt = CarbonImmutable::now();
    }

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function setUser(UserInterface $user, bool $updateRelation = true): void
    {
        $this->user = $user;

        if ($updateRelation) {
            $user->addOrganizationMember($this, false);
        }
    }

    public function getOrganization(): ?OrganizationInterface
    {
        return $this->organization;
    }

    public function setOrganization(OrganizationInterface $organization, bool $updateRelation = true): void
    {
        $this->organization = $organization;

        if ($updateRelation) {
            $organization->addMember($this, false);
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
