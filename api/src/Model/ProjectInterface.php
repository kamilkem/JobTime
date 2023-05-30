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

interface ProjectInterface extends UserResourceInterface
{
    public function getOrganization(): ?OrganizationInterface;

    public function setOrganization(OrganizationInterface $organization, bool $updateRelation = true): void;

    public function getName(): ?string;

    public function setName(string $name): void;

    /**
     * @return Collection<TaskInterface>
     */
    public function getTasks(): Collection;

    public function addTask(TaskInterface $task, bool $updateRelation = true): void;

    public function removeTask(TaskInterface $task): void;

    /**
     * @return Collection<ProjectIntegrationInterface>
     */
    public function getIntegrations(): Collection;

    public function addIntegration(ProjectIntegrationInterface $integration, bool $updateRelation = true): void;

    public function removeIntegration(ProjectIntegrationInterface $integration): void;
}
