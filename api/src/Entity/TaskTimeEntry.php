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
use App\Model\TaskInterface;
use App\Model\TaskTimeEntryInterface;
use App\Model\UserResourceTrait;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToOne;

#[API\ApiResource(
    operations: []
)]
#[Entity]
class TaskTimeEntry implements TaskTimeEntryInterface
{
    use UserResourceTrait;

    public function __construct(
        #[ManyToOne(targetEntity: Task::class, cascade: ['persist'], inversedBy: 'timeEntries')]
        private TaskInterface $task,
        #[Column(type: 'carbon_immutable')]
        private CarbonInterface $startDate,
        #[Column(type: 'carbon_immutable', nullable: true)]
        private ?CarbonInterface $endDate
    ) {
        $this->createdAt = CarbonImmutable::now();
    }

    public function getTask(): TaskInterface
    {
        return $this->task;
    }

    public function setTask(TaskInterface $task, bool $updateRelation = true): void
    {
        $this->task = $task;

        if ($updateRelation) {
            $task->addTimeEntry($this, false);
        }
    }

    public function getStartDate(): CarbonInterface
    {
        return $this->startDate;
    }

    public function setStartDate(CarbonInterface $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function getEndDate(): ?CarbonInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?CarbonInterface $endDate): void
    {
        $this->endDate = $endDate;
    }
}
