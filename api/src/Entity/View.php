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
use App\Model\DescriptionTrait;
use App\Model\DirectoryInterface;
use App\Model\NameTrait;
use App\Model\TaskInterface;
use App\Model\TeamInterface;
use App\Model\UserResourceTrait;
use App\Model\ViewInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[API\ApiResource(
    operations: []
)]
#[ORM\Entity]
final class View implements ViewInterface
{
    use UserResourceTrait;
    use NameTrait;
    use DescriptionTrait;

    public const GROUP_READ = 'view:read';
    public const GROUP_WRITE = 'view:write';

    #[ORM\ManyToOne(targetEntity: Directory::class, cascade: ['persist'], inversedBy: 'views')]
    #[Groups(groups: [self::GROUP_READ])]
    private DirectoryInterface $directory;

    /**
     * @var Collection<TaskInterface>
     */
    #[ORM\OneToMany(
        mappedBy: 'view',
        targetEntity: Task::class,
        cascade: [
            'persist',
            'remove'
        ],
        orphanRemoval: true
    )]
    #[Groups(groups: [self::GROUP_READ])]
    private Collection $tasks;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
    }

    public function getTeam(): TeamInterface
    {
        return $this->directory->getTeam();
    }

    public function getDirectory(): DirectoryInterface
    {
        return $this->directory;
    }

    public function setDirectory(DirectoryInterface $directory, bool $updateRelation = true): void
    {
        $this->directory = $directory;

        if ($updateRelation) {
            $directory->addView($this, false);
        }
    }

    /**
     * @return  Collection<TaskInterface>
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
            $task->setView($this, false);
        }
    }

    public function removeTask(TaskInterface $task): void
    {
        $this->tasks->removeElement($task);
    }
}
