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
use App\Model\UserResourceTrait;
use App\Security\OrganizationVoter;
use App\State\CreateProjectProcessor;
use App\State\OrganizationSubresourceCollectionProvider;
use Carbon\CarbonImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Validator\Constraints as Assert;

#[API\ApiResource(
    uriTemplate: '/projects/{organization}/{project}.{_format}',
    operations: [
        new API\Get(
            security: 'is_granted(\'' . OrganizationVoter::IS_USER_MEMBER . '\', object.getOrganization())',
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
        'project' => new API\Link(fromClass: self::class),
    ],
    normalizationContext: [
        AbstractNormalizer::GROUPS => [self::GROUP_READ]
    ],
    denormalizationContext: [
        AbstractNormalizer::GROUPS => [self::GROUP_WRITE]
    ],
)]
#[API\ApiResource(
    uriTemplate: '/orgs/{organization}/projects.{_format}',
    operations: [
        new API\GetCollection(
            provider: OrganizationSubresourceCollectionProvider::class,
        ),
        new API\Post(
            read: false,
            processor: CreateProjectProcessor::class,
        ),
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
class Project implements ProjectInterface
{
    use UserResourceTrait;

    public const GROUP_READ = 'project:read';
    public const GROUP_WRITE = 'project:write';

    /**
     * @var Collection<TaskInterface>
     */
    #[ORM\OneToMany(
        mappedBy: 'project',
        targetEntity: Task::class,
        cascade: [
            'persist',
            'remove'
        ],
        orphanRemoval: true
    )]
    #[Groups(groups: [self::GROUP_READ])]
    private Collection $tasks;

    /**
     * @var Collection<ProjectIntegrationInterface>
     */
    #[ORM\OneToMany(
        mappedBy: 'project',
        targetEntity: ProjectIntegration::class,
        cascade: [
            'persist',
            'remove'
        ],
        orphanRemoval: true
    )]
    #[Groups(groups: [self::GROUP_READ])]
    private Collection $integrations;

    public function __construct(
        #[ORM\Column]
        #[Assert\NotBlank]
        #[Groups(groups: [self::GROUP_READ, self::GROUP_WRITE])]
        private ?string $name = null,
        #[ORM\ManyToOne(targetEntity: Organization::class, cascade: ['persist'], inversedBy: 'projects')]
        #[Groups(groups: [self::GROUP_READ])]
        private ?OrganizationInterface $organization = null,
    ) {
        $this->createdAt = CarbonImmutable::now();
        $this->tasks = new ArrayCollection();
        $this->integrations = new ArrayCollection();
    }

    public function getOrganization(): ?OrganizationInterface
    {
        return $this->organization;
    }

    public function setOrganization(OrganizationInterface $organization, bool $updateRelation = true): void
    {
        $this->organization = $organization;

        if ($updateRelation) {
            $organization->addProject($this, false);
        }
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
     * @return Collection<TaskInterface>
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(TaskInterface $task, bool $updateRelation = true): void
    {
        if ($this->tasks->contains($task)) {
            return;
        }

        $this->tasks->add($task);
        if ($updateRelation) {
            $task->setProject($this, false);
        }
    }

    public function removeTask(TaskInterface $task): void
    {
        $this->tasks->removeElement($task);
    }

    /**
     * @return Collection<ProjectIntegrationInterface>
     */
    public function getIntegrations(): Collection
    {
        return $this->integrations;
    }

    public function addIntegration(ProjectIntegrationInterface $integration, bool $updateRelation = true): void
    {
        if ($this->integrations->contains($integration)) {
            return;
        }

        $this->integrations->add($integration);
        if ($updateRelation) {
            $integration->setProject($this, false);
        }
    }

    public function removeIntegration(ProjectIntegrationInterface $integration): void
    {
        $this->integrations->removeElement($integration);
    }
}
