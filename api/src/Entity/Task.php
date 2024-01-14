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
use App\Model\DescriptionTrait;
use App\Model\NameTrait;
use App\Model\ResourceInterface;
use App\Model\TaskInterface;
use App\Model\TeamInterface;
use App\Model\TimeEntryInterface;
use App\Model\UserInterface;
use App\Model\UserResourceTrait;
use App\Model\ViewInterface;
use App\Security\TeamVoter;
use App\State\TeamSubresourceCollectionProvider;
use Carbon\CarbonImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

#[API\ApiResource(
    uriTemplate: '/tasks/{task}.{_format}',
    operations: [
        new API\Get(
            security: 'is_granted(\'' . TeamVoter::IS_USER_MEMBER . '\', object.getTeam())',
        ),
        new API\Patch(
            security: 'is_granted(\'' . TeamVoter::IS_USER_MEMBER . '\', object.getTeam())',
        ),
        new API\Delete(
            security: 'is_granted(\'' . TeamVoter::IS_USER_MEMBER . '\', object.getTeam())',
        ),
    ],
    uriVariables: [
        'task' => new API\Link(fromClass: self::class),
    ],
    normalizationContext: [
        AbstractNormalizer::GROUPS => self::AGGREGATE_READ_GROUPS,
    ],
    denormalizationContext: [
        AbstractNormalizer::GROUPS => self::AGGREGATE_WRITE_GROUPS,
    ],
)]
#[API\ApiResource(
    uriTemplate: '/view/{view}/tasks.{_format}',
    operations: [
        new API\GetCollection(
            provider: TeamSubresourceCollectionProvider::class,
        ),
        new API\Post(
            read: false,
        ),
    ],
    uriVariables: [
        'view' => new API\Link(toProperty: 'view', fromClass: View::class),
    ],
    normalizationContext: [
        AbstractNormalizer::GROUPS => self::AGGREGATE_READ_GROUPS,
    ],
    denormalizationContext: [
        AbstractNormalizer::GROUPS => self::AGGREGATE_WRITE_GROUPS,
    ],
)]
#[API\ApiResource(
    uriTemplate: '/user/tasks.{_format}',
    operations: [
        new API\GetCollection()
    ],
    normalizationContext: [
        AbstractNormalizer::GROUPS => self::AGGREGATE_READ_GROUPS,
    ],
    denormalizationContext: [
        AbstractNormalizer::GROUPS => self::AGGREGATE_WRITE_GROUPS,
    ],
)]
#[ORM\Entity]
class Task implements TaskInterface
{
    use UserResourceTrait;
    use NameTrait;
    use DescriptionTrait;

    #[ORM\ManyToOne(targetEntity: View::class, cascade: ['persist'], inversedBy: 'tasks')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(groups: [self::GROUP_READ])]
    private ViewInterface $view;

    /**
     * @var Collection<TimeEntryInterface>
     */
    #[ORM\OneToMany(
        mappedBy: 'task',
        targetEntity: TimeEntry::class,
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

    public function __construct()
    {
        $this->createdAt = CarbonImmutable::now();
        $this->timeEntries = new ArrayCollection();
        $this->assignedUsers = new ArrayCollection();
    }

    public function getTeam(): ?TeamInterface
    {
        return $this->view->getTeam();
    }

    public function getView(): ViewInterface
    {
        return $this->view;
    }

    public function setView(ViewInterface $view, bool $updateRelation = true): void
    {
        $this->view = $view;

        if ($updateRelation) {
            $view->addTask($this, false);
        }
    }

    /**
     * @return Collection<TimeEntryInterface>
     */
    public function getTimeEntries(): Collection
    {
        return $this->timeEntries;
    }

    public function addTimeEntry(TimeEntryInterface $timeEntry, bool $updateRelation = true): void
    {
        if ($this->timeEntries->contains($timeEntry)) {
            return;
        }

        $this->timeEntries->add($timeEntry);
        if ($updateRelation) {
            $timeEntry->setTask($this, false);
        }
    }

    public function removeTimeEntry(TimeEntryInterface $timeEntry): void
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
