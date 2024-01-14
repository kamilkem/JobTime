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

namespace App\Model;

use Carbon\CarbonInterface;

interface TimeEntryInterface extends UserResourceInterface
{
    public const string GROUP_READ = 'time_entry:read';
    public const string GROUP_WRITE = 'time_entry:write';

    public const array AGGREGATE_READ_GROUPS = [self::GROUP_READ, ResourceInterface::GROUP_READ];
    public const array AGGREGATE_WRITE_GROUPS = [self::GROUP_WRITE, ResourceInterface::GROUP_WRITE];

    public function getTask(): TaskInterface;

    public function setTask(TaskInterface $task, bool $updateRelation = true): void;

    public function getStartDate(): CarbonInterface;

    public function setStartDate(CarbonInterface $startDate): void;

    public function getEndDate(): ?CarbonInterface;

    public function setEndDate(?CarbonInterface $endDate): void;
}
