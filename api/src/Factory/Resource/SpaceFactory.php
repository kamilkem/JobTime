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

use App\Entity\Space;
use App\Model\SpaceInterface;
use App\Model\TeamInterface;

final readonly class SpaceFactory extends AbstractResourceFactory implements SpaceFactoryInterface
{
    public function create(TeamInterface $team, string $name, ?string $description = null): SpaceInterface
    {
        $space = new Space($team, $name, $description);

        $this->dispatchResourceWasCreatedEvent($space);

        return $space;
    }
}
