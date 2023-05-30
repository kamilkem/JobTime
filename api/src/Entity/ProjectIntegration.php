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
use App\Model\IntegrationServiceEnum;
use App\Model\IntegrationStatusEnum;
use App\Model\ProjectIntegrationInterface;
use App\Model\ProjectInterface;
use App\Model\TaskInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[API\ApiResource(
    operations: []
)]
#[ORM\Entity]
class ProjectIntegration extends AbstractIntegration implements ProjectIntegrationInterface
{
    /**
     * @var Collection<TaskInterface>
     */
    #[ORM\OneToMany(
        mappedBy: 'projectIntegration',
        targetEntity: Task::class,
        cascade: [
            'persist',
            'remove'
        ],
        orphanRemoval: true
    )]
    private Collection $tasks;

    public function __construct(
        #[ORM\ManyToOne(targetEntity: Project::class, cascade: ['persist'], inversedBy: 'integrations')]
        private ProjectInterface $project,
        IntegrationServiceEnum $service,
        IntegrationStatusEnum $status
    ) {
        parent::__construct($service, $status);

        $this->tasks = new ArrayCollection();
    }

    public function getProject(): ProjectInterface
    {
        return $this->project;
    }

    public function setProject(ProjectInterface $project, bool $updateRelation = true): void
    {
        $this->project = $project;

        if ($updateRelation) {
            $project->addIntegration($this, false);
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
            $task->setProjectIntegration($this, false);
        }
    }

    public function removeTask(TaskInterface $task, bool $updateRelation = true): void
    {
        $this->tasks->removeElement($task);

        if ($updateRelation) {
            $task->setProjectIntegration(null, false);
        }
    }
}
