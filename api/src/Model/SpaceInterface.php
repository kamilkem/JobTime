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

interface SpaceInterface extends UserResourceInterface, NameInterface, DescriptionInterface
{
    public function getTeam(): TeamInterface;

    public function setTeam(TeamInterface $team, bool $updateRelation = true): void;

    /**
     * @return Collection<TaskInterface>
     */
    public function getDirectories(): Collection;

    public function addDirectory(DirectoryInterface $directory, bool $updateRelation = true): void;

    public function removeDirectory(DirectoryInterface $directory): void;
}
