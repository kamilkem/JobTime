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

interface ProjectGroupInterface extends IdentifiableInterface, CreatedAtInterface
{
    public function getName(): string;

    public function setName(string $name): void;

    /**
     * @return Collection<ProjectInterface>
     */
    public function getProjects(): Collection;

    public function addProject(ProjectInterface $project): void;

    public function removeProject(ProjectInterface $project): void;
}
