<?php

/**
 * This file is part of the JobTime package.
 *
 * (c) Kamil Kozaczyński <kozaczynski.kamil@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Model;

interface CreatedByUserInterface
{
    public function getCreatedBy(): UserInterface;

    public function setCreatedBy(UserInterface $createdBy): void;
}
