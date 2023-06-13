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
use App\Model\ProjectIntegrationInterface;
use App\Model\ProjectInterface;
use App\Model\TaskInterface;
use App\Model\TaskTimeEntryInterface;
use App\Model\UserInterface;
use App\Model\UserResourceTrait;
use App\Security\OrganizationVoter;
use App\State\OrganizationSubresourceCollectionProvider;
use Carbon\CarbonImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Validator\Constraints as Assert;

#[API\ApiResource(
    uriTemplate: '/projects/{organization}/{project}/tasks/{task}.{_format}',
    operations: [
        new API\Get(
            security: 'is_granted(\'' . OrganizationVoter::IS_USER_MEMBER . '\', object.getOrganization())',
        ),
        new API\Patch(
            security: 'is_granted(\'' . OrganizationVoter::IS_USER_MEMBER . '\', object.getOrganization())',
        ),
        new API\Delete(
            security: 'is_granted(\'' . OrganizationVoter::IS_USER_MEMBER . '\', object.getOrganization())',
        ),
    ],
    uriVariables: [
        'organization' => new API\Link(toProperty: 'organization', fromClass: Organization::class),
        'project' => new API\Link(toProperty: 'project', fromClass: Project::class),
        'task' => new API\Link(fromClass: self::class),
    ],
    normalizationContext: [
        AbstractNormalizer::GROUPS => [self::GROUP_READ]
    ],
    denormalizationContext: [
        AbstractNormalizer::GROUPS => [self::GROUP_WRITE]
    ],
)]
#[API\ApiResource(
    uriTemplate: '/projects/{organization}/{project}/tasks.{_format}',
    operations: [
        new API\GetCollection(
            provider: OrganizationSubresourceCollectionProvider::class,
        ),
        new API\Post(
            read: false,
        ),
    ],
    uriVariables: [
        'organization' => new API\Link(toProperty: 'organization', fromClass: Organization::class),
        'project' => new API\Link(toProperty: 'project', fromClass: Project::class),
    ],
    normalizationContext: [
        AbstractNormalizer::GROUPS => [self::GROUP_READ]
    ],
    denormalizationContext: [
        AbstractNormalizer::GROUPS => [self::GROUP_WRITE]
    ],
)]
#[API\ApiResource(
    uriTemplate: '/user/tasks.{_format}',
    operations: [
        new API\GetCollection()
    ],
    normalizationContext: [
        AbstractNormalizer::GROUPS => [self::GROUP_READ]
    ],
    denormalizationContext: [
        AbstractNormalizer::GROUPS => [self::GROUP_WRITE]
    ],
)]
#[ORM\Entity]
class Task implements TaskInterface
{
    use UserResourceTrait;

    public const GROUP_READ = 'task:read';
    public const GROUP_WRITE = 'task:write';

    /**
     * @var Collection<TaskTimeEntryInterface>
     */
    #[ORM\OneToMany(
        mappedBy: 'task',
        targetEntity: TaskTimeEntry::class,
        cascade: [
            'persist',
            'remove'
        ],
        orphanRemoval: true
    )]
    private Collection $timeEntries;

    /**
     * @var Collection<UserInterface>
     */
    #[ORM\ManyToMany(targetEntity: User::class, cascade: ['persist'])]
    private Collection $assignedUsers;

    public function __construct(
        #[ORM\ManyToOne(targetEntity: Project::class, cascade: ['persist'], inversedBy: 'tasks')]
        private ProjectInterface $project,
        #[ORM\Column]
        #[Assert\NotBlank]
        #[Groups(groups: [self::GROUP_READ, self::GROUP_WRITE])]
        private string $name,
        #[ORM\ManyToOne(targetEntity: ProjectIntegration::class, cascade: ['persist'], inversedBy: 'tasks')]
        private ?ProjectIntegrationInterface $projectIntegration = null,
    ) {
        $this->createdAt = CarbonImmutable::now();
        $this->timeEntries = new ArrayCollection();
        $this->assignedUsers = new ArrayCollection();
    }

    public function getOrganization(): ?OrganizationInterface
    {
        return $this->project->getOrganization();
    }

    public function getProject(): ProjectInterface
    {
        return $this->project;
    }

    public function setProject(ProjectInterface $project, bool $updateRelation = true): void
    {
        $this->project = $project;

        if ($updateRelation) {
            $project->addTask($this, false);
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getProjectIntegration(): ?ProjectIntegrationInterface
    {
        return $this->projectIntegration;
    }

    public function setProjectIntegration(
        ?ProjectIntegrationInterface $projectIntegration,
        bool $updateRelation = true
    ): void {
        $this->projectIntegration = $projectIntegration;

        if ($projectIntegration && $updateRelation) {
            $projectIntegration->addTask($this, false);
        }
    }

    /**
     * @return Collection<TaskTimeEntryInterface>
     */
    public function getTimeEntries(): Collection
    {
        return $this->timeEntries;
    }

    public function addTimeEntry(TaskTimeEntryInterface $timeEntry, bool $updateRelation = true): void
    {
        if ($this->timeEntries->contains($timeEntry)) {
            return;
        }

        $this->timeEntries->add($timeEntry);
        if ($updateRelation) {
            $timeEntry->setTask($this, false);
        }
    }

    public function removeTimeEntry(TaskTimeEntryInterface $timeEntry): void
    {
        $this->timeEntries->removeElement($timeEntry);
    }

    /**
     * @return Collection<UserInterface>
     */
    public function getAssignedUsers(): Collection
    {
        return $this->assignedUsers;
    }

    public function addAssignedUser(UserInterface $user): void
    {
        if ($this->assignedUsers->contains($user)) {
            return;
        }

        $this->assignedUsers->add($user);
    }

    public function removeAssignedUser(UserInterface $user): void
    {
        $this->assignedUsers->removeElement($user);
    }
}
