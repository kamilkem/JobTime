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

interface TaskInterface extends IdentifiableInterface, CreatedAtInterface
{
    public function getName(): string;

    public function setName(string $name): void;

    /**
     * @return Collection<TaskTimeEntryInterface>
     */
    public function getTimeEntries(): Collection;

    public function addTimeEntry(TaskTimeEntryInterface $taskTimeEntry): void;

    public function removeTimeEntry(TaskTimeEntryInterface $taskTimeEntry): void;

    /**
     * @return Collection<UserInterface>
     */
    public function getAssignedUsers(): Collection;

    public function addAssignedUser(UserInterface $user): void;

    public function removeAssignedUser(UserInterface $user): void;
}
