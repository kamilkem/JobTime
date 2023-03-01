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

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Model\CreatedAtTrait;
use App\Model\IdentifiableTrait;
use App\Model\TaskInterface;
use App\Model\TaskTimeEntryInterface;
use App\Model\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\Entity]
class Task implements TaskInterface
{
    use IdentifiableTrait;
    use CreatedAtTrait;

    /**
     * @var Collection<TaskTimeEntryInterface>
     */
    #[ORM\OneToMany(mappedBy: 'task', targetEntity: TaskTimeEntry::class)]
    private Collection $timeEntries;

    /**
     * @var Collection<UserInterface>
     */
    #[ORM\ManyToMany(targetEntity: User::class)]
    private Collection $assignedUsers;

    public function __construct(
        #[ORM\Column]
        private string $name,
    ) {
        $this->timeEntries = new ArrayCollection();
        $this->assignedUsers = new ArrayCollection();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return Collection<TaskTimeEntryInterface>
     */
    public function getTimeEntries(): Collection
    {
        return $this->timeEntries;
    }

    public function addTimeEntry(TaskTimeEntryInterface $taskTimeEntry): void
    {
        $this->timeEntries->add($taskTimeEntry);
    }

    public function removeTimeEntry(TaskTimeEntryInterface $taskTimeEntry): void
    {
        $this->timeEntries->removeElement($taskTimeEntry);
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
        $this->assignedUsers->add($user);
    }

    public function removeAssignedUser(UserInterface $user): void
    {
        $this->assignedUsers->removeElement($user);
    }
}
