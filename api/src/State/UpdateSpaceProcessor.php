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

namespace App\State;

use ApiPlatform\Metadata\Operation;
use App\Dto\SpaceInput;
use App\Model\SpaceInterface;

readonly class UpdateSpaceProcessor extends AbstractUpdateProcessor
{
    protected function prepare(mixed $data, Operation $operation, object $object): SpaceInterface
    {
        if (!$data instanceof SpaceInput || !$object instanceof SpaceInterface) {
            throw new \RuntimeException();
        }

        $object->setTeam($data->team);
        $object->setName($data->name);
        $object->setDescription($data->description);

        return $object;
    }
}
