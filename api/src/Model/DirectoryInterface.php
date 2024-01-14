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

interface DirectoryInterface extends UserResourceInterface, NameInterface
{
    public const string GROUP_READ = 'directory:read';
    public const string GROUP_WRITE = 'directory:write';

    public const array AGGREGATE_READ_GROUPS = [self::GROUP_READ, ResourceInterface::GROUP_READ];
    public const array AGGREGATE_WRITE_GROUPS = [self::GROUP_WRITE, ResourceInterface::GROUP_WRITE];

    public function getTeam(): TeamInterface;

    public function getSpace(): SpaceInterface;

    public function setSpace(SpaceInterface $space, bool $updateRelation = true): void;

    public function getDirectory(): ?DirectoryInterface;

    public function setDirectory(?DirectoryInterface $directory, bool $updateRelation = true): void;

    /**
     * @return Collection<ViewInterface>
     */
    public function getViews(): Collection;

    public function addView(ViewInterface $view, bool $updateRelation = true): void;

    public function removeView(ViewInterface $view): void;

    /**
     * @return Collection<DirectoryInterface>
     */
    public function getSubDirectories(): Collection;

    public function addSubDirectory(DirectoryInterface $directory, bool $updateRelation = true): void;

    public function removeSubDirectory(DirectoryInterface $directory): void;
}
