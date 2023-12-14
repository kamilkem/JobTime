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

use Doctrine\Common\Collections\Collection;

interface TaskInterface extends UserResourceInterface, NameInterface, DescriptionInterface
{
    public function getTeam(): ?TeamInterface;

    public function getView(): ViewInterface;

    public function setView(ViewInterface $view, bool $updateRelation = true): void;

    /**
     * @return Collection<TimeEntryInterface>
     */
    public function getTimeEntries(): Collection;

    public function addTimeEntry(TimeEntryInterface $timeEntry, bool $updateRelation = true): void;

    public function removeTimeEntry(TimeEntryInterface $timeEntry): void;

    /**
     * @return Collection<UserInterface>
     */
    public function getAssignedUsers(): Collection;

    public function addAssignedUser(UserInterface $user): void;

    public function removeAssignedUser(UserInterface $user): void;
}
