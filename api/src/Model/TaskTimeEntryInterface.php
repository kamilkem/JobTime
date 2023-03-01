<?php

/**
 * This file is part of the jobtime-backend package.
 *
 * (c) Kamil Kozaczyński <kozaczynski.kamil@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Model;

use Carbon\CarbonInterface;

interface TaskTimeEntryInterface extends IdentifiableInterface, CreatedAtInterface
{
    public function getTask(): TaskInterface;

    public function setTask(TaskInterface $task): void;

    public function getStartDate(): CarbonInterface;

    public function setStartDate(CarbonInterface $startDate): void;

    public function getEndDate(): ?CarbonInterface;

    public function setEndDate(?CarbonInterface $endDate): void;
}
