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
use App\Model\IdentifiableTrait;
use App\Model\TaskInterface;
use App\Model\TaskTimeEntryInterface;
use Carbon\CarbonInterface;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToOne;

#[ApiResource]
#[Entity]
class TaskTimeEntry implements TaskTimeEntryInterface
{
    use IdentifiableTrait;
    use CreatedAtTrait;

    public function __construct(
        #[ManyToOne(targetEntity: Task::class, inversedBy: 'taskTimeEntries')]
        private TaskInterface $task,
        #[Column(type: 'carbon_immutable')]
        private CarbonInterface $startDate,
        #[Column(type: 'carbon_immutable', nullable: true)]
        private ?CarbonInterface $endDate
    ) {
    }

    public function getTask(): TaskInterface
    {
        return $this->task;
    }

    public function setTask(TaskInterface $task): void
    {
        $this->task = $task;
        $this->task->addTimeEntry($this);
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
