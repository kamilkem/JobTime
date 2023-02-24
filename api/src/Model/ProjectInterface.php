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

namespace App\Model;

interface ProjectInterface extends IdentifiableInterface
{
    public function getName(): string;

    public function setName(string $name): void;

    public function getProjectGroup(): ?ProjectGroupInterface;

    public function setProjectGroup(?ProjectGroupInterface $projectGroup): void;
}
