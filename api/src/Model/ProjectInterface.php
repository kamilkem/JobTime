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

interface ProjectInterface extends IdentifiableInterface, CreatedAtInterface, CreatedByUserInterface
{
    public function getName(): string;

    public function setName(string $name): void;

    public function getGroup(): ?ProjectGroupInterface;

    public function setGroup(?ProjectGroupInterface $group, bool $updateRelation = true): void;

    /**
     * @return Collection<TaskInterface>
     */
    public function getTasks(): Collection;

    public function addTask(TaskInterface $task, bool $updateRelation = true): void;

    public function removeTask(TaskInterface $task): void;
}
