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

interface ProjectIntegrationInterface extends IntegrationInterface
{
    public function getProject(): ProjectInterface;

    public function setProject(ProjectInterface $project, bool $updateRelation = true): void;

    /**
     * @return Collection<TaskInterface>
     */
    public function getTasks(): Collection;

    public function addTask(TaskInterface $task, bool $updateRelation = true): void;

    public function removeTask(TaskInterface $task, bool $updateRelation = true): void;
}
