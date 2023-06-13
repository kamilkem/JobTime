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
use App\Model\OrganizationInterface;
use App\Model\OrganizationInvitationInterface;
use App\Model\OrganizationMemberInterface;
use App\Model\ProjectInterface;
use App\Model\ResourceTrait;
use App\Model\UserInterface;
use App\Security\OrganizationVoter;
use App\State\CreateOrganizationProcessor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Validator\Constraints as Assert;

#[API\ApiResource(
    uriTemplate: '/orgs.{_format}',
    operations: [
        new API\Post(
            processor: CreateOrganizationProcessor::class,
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
    uriTemplate: '/orgs/{organization}.{_format}',
    operations: [
        new API\Get(
            security: 'is_granted(\'' . OrganizationVoter::IS_USER_MEMBER . '\', object)'
        ),
        new API\Patch(
            security: 'is_granted(\'' . OrganizationVoter::IS_USER_OWNER . '\', object)'
        ),
        new API\Delete(
            security: 'is_granted(\'' . OrganizationVoter::IS_USER_OWNER . '\', object)'
        ),
    ],
    uriVariables: [
        'organization' => new API\Link(fromClass: self::class)
    ],
    normalizationContext: [
        AbstractNormalizer::GROUPS => [self::GROUP_READ]
    ],
    denormalizationContext: [
        AbstractNormalizer::GROUPS => [self::GROUP_WRITE]
    ],
)]
#[API\ApiResource(
    uriTemplate: '/user/orgs.{_format}',
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
class Organization implements OrganizationInterface
{
    use ResourceTrait;

    public const GROUP_READ = 'organization:read';
    public const GROUP_WRITE = 'organization:write';

    /**
     * @var Collection<OrganizationMemberInterface>
     */
    #[ORM\OneToMany(
        mappedBy: 'organization',
        targetEntity: OrganizationMember::class,
        cascade: [
            'persist',
            'remove'
        ],
        orphanRemoval: true
    )]
    #[Groups(groups: [self::GROUP_READ])]
    private Collection $members;

    #[ORM\OneToMany(
        mappedBy: 'organization',
        targetEntity: OrganizationInvitation::class,
        cascade: [
            'persist',
            'remove'
        ],
        orphanRemoval: true
    )]
    private Collection $invitations;

    /**
     * @var Collection<ProjectInterface>
     */
    #[ORM\OneToMany(
        mappedBy: 'organization',
        targetEntity: Project::class,
        cascade: [
            'persist',
            'remove'
        ],
        orphanRemoval: true
    )]
    #[Groups(groups: [self::GROUP_READ])]
    private Collection $projects;

    public function __construct(
        #[ORM\Column]
        #[Assert\NotBlank]
        #[Groups(groups: [self::GROUP_READ, self::GROUP_WRITE])]
        private ?string $name = null,
    ) {
        $this->members = new ArrayCollection();
        $this->invitations = new ArrayCollection();
        $this->projects = new ArrayCollection();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return Collection<OrganizationMemberInterface>
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(OrganizationMemberInterface $organizationMember, bool $updateRelation = true): void
    {
        if ($this->members->contains($organizationMember)) {
            return;
        }

        $this->members->add($organizationMember);
        if ($updateRelation) {
            $organizationMember->setOrganization($this, false);
        }
    }

    public function removeMember(OrganizationMemberInterface $organizationMember): void
    {
        $this->members->removeElement($organizationMember);
    }

    /**
     * @return Collection<OrganizationInvitationInterface>
     */
    public function getInvitations(): Collection
    {
        return $this->invitations;
    }

    public function addInvitation(
        OrganizationInvitationInterface $organizationInvitation,
        bool $updateRelation = true
    ): void {
        if ($this->invitations->contains($organizationInvitation)) {
            return;
        }

        $this->invitations->add($organizationInvitation);
        if ($updateRelation) {
            $organizationInvitation->setOrganization($this, false);
        }
    }

    public function removeInvitation(OrganizationInvitation $organizationInvitation): void
    {
        $this->invitations->removeElement($organizationInvitation);
    }

    /**
     * @return Collection<ProjectInterface>
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(ProjectInterface $project, bool $updateRelation = true): void
    {
        if ($this->projects->contains($project)) {
            return;
        }

        $this->projects->add($project);
        if ($updateRelation) {
            $project->setOrganization($this, false);
        }
    }

    public function removeProject(ProjectInterface $project): void
    {
        $this->projects->removeElement($project);
    }

    public function isUserMember(UserInterface $user): bool
    {
        return $this->members->exists(
            static function (int|string $key, mixed $organizationMember) use ($user) {
                /** @var OrganizationMemberInterface $organizationMember */
                return $organizationMember->getUser() === $user;
            }
        );
    }

    public function isUserOwner(UserInterface $user): bool
    {
        return $this->members->exists(
            static function (int|string $key, mixed $organizationMember) use ($user) {
                /** @var OrganizationMemberInterface $organizationMember */
                return $organizationMember->getUser() === $user && $organizationMember->isOwner();
            }
        );
    }
}
