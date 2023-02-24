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
use App\Model\IdentifiableTrait;
use App\Model\TaskInterface;
use App\Model\TaskTimeEntryInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\Entity]
class Task implements TaskInterface
{
    use IdentifiableTrait;

    /**
     * @var Collection<TaskTimeEntryInterface>
     */
    #[ORM\OneToMany(mappedBy: 'task', targetEntity: TaskTimeEntry::class)]
    private Collection $taskTimeEntries;

    public function __construct(
        #[ORM\Column]
        private string $name,
    ) {
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
    public function getTaskTimeEntries(): Collection
    {
        return $this->taskTimeEntries;
    }

    public function addTaskTimeEntry(TaskTimeEntryInterface $taskTimeEntry): void
    {
        $this->taskTimeEntries->add($taskTimeEntry);
    }

    public function removeTaskTimeEntry(TaskTimeEntryInterface $taskTimeEntry): void
    {
        $this->taskTimeEntries->removeElement($taskTimeEntry);
    }
}
