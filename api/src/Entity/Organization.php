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
use App\Model\OrganizationUserInterface;
use App\Model\ProjectInterface;
use App\Model\ResourceTrait;
use App\Model\UserInterface;
use App\Security\OrganizationVoter;
use App\State\CreateOrganizationProcessor;
use Carbon\CarbonImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Validator\Constraints as Assert;

#[API\ApiResource(
    operations: [
        new API\GetCollection(),
        new API\Post(
            processor: CreateOrganizationProcessor::class,
        ),
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
    normalizationContext: [
        AbstractNormalizer::GROUPS => [self::GROUP_READ]
    ],
    denormalizationContext: [
        AbstractNormalizer::GROUPS => [self::GROUP_WRITE]
    ]
)]
#[ORM\Entity]
class Organization implements OrganizationInterface
{
    use ResourceTrait;

    public const GROUP_READ = 'organization:read';
    public const GROUP_WRITE = 'organization:write';

    /**
     * @var Collection<OrganizationUserInterface>
     */
    #[ORM\OneToMany(
        mappedBy: 'organization',
        targetEntity: OrganizationUser::class,
        cascade: [
            'persist',
            'remove'
        ],
        orphanRemoval: true
    )]
    #[Groups(groups: [self::GROUP_READ])]
    private Collection $organizationUsers;

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
        $this->createdAt = CarbonImmutable::now();
        $this->organizationUsers = new ArrayCollection();
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
     * @return Collection<OrganizationUserInterface>
     */
    public function getOrganizationUsers(): Collection
    {
        return $this->organizationUsers;
    }

    public function addOrganizationUser(OrganizationUserInterface $organizationUser, bool $updateRelation = true): void
    {
        if ($this->organizationUsers->contains($organizationUser)) {
            return;
        }

        $this->organizationUsers->add($organizationUser);
        if ($updateRelation) {
            $organizationUser->setOrganization($this, false);
        }
    }

    public function removeOrganizationUser(OrganizationUserInterface $organizationUser): void
    {
        $this->organizationUsers->removeElement($organizationUser);
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
        return $this->organizationUsers->contains($user);
    }

    public function isUserOwner(UserInterface $user): bool
    {
        return $this->organizationUsers->exists(
            static function (int|string $key, mixed $organizationUser) use ($user) {
                /** @var OrganizationUserInterface $organizationUser */
                return $organizationUser->getUser() === $user && $organizationUser->isOwner();
            }
        );
    }
}
