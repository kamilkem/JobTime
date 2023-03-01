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

use ApiPlatform\Metadata\ApiResource;
use App\Model\CreatedAtTrait;
use App\Model\CreatedByUserTrait;
use App\Model\IdentifiableTrait;
use App\Model\ProjectGroupInterface;
use App\Model\ProjectInterface;
use App\Model\TaskInterface;
use Carbon\CarbonImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\Entity]
class Project implements ProjectInterface
{
    use IdentifiableTrait;
    use CreatedAtTrait;
    use CreatedByUserTrait;

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
    private Collection $tasks;

    public function __construct(
        #[ORM\Column]
        private string $name,
        #[ORM\ManyToOne(targetEntity: ProjectGroup::class, cascade: ['persist'], inversedBy: 'projects')]
        private ?ProjectGroupInterface $group = null
    ) {
        $this->createdAt = CarbonImmutable::now();
        $this->tasks = new ArrayCollection();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getGroup(): ?ProjectGroupInterface
    {
        return $this->group;
    }

    public function setGroup(?ProjectGroupInterface $group, bool $updateRelation = true): void
    {
        $this->group = $group;

        if ($updateRelation) {
            $group->addProject($this, false);
        }
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
}
