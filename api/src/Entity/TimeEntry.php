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
use App\Model\TimeEntryInterface;
use App\Model\UserResourceTrait;
use Carbon\CarbonInterface;
use Doctrine\ORM\Mapping as ORM;

#[API\ApiResource(
    operations: []
)]
#[ORM\Entity]
class TimeEntry implements TimeEntryInterface
{
    use UserResourceTrait;

    #[ORM\ManyToOne(targetEntity: Task::class, cascade: ['persist'], inversedBy: 'timeEntries')]
    #[ORM\JoinColumn(nullable: false)]
    private TaskInterface $task;

    #[ORM\Column(type: 'carbon_immutable')]
    private CarbonInterface $startDate;

    #[ORM\Column(type: 'carbon_immutable', nullable: true)]
    private ?CarbonInterface $endDate;

    public function __construct()
    {
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
