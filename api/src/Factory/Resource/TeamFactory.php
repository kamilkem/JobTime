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

namespace App\Factory\Resource;

use App\Entity\Team;
use App\Model\TeamInterface;

final readonly class TeamFactory implements TeamFactoryInterface
{
    public function create(string $name): TeamInterface
    {
        return new Team($name);
    }
}
