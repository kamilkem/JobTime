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
use App\Dto\TeamInput;
use App\Model\TeamInterface;

readonly class UpdateTeamProcessor extends AbstractUpdateProcessor
{
    protected function prepare(mixed $data, Operation $operation, object $object): TeamInterface
    {
        if (!$data instanceof TeamInput || !$object instanceof TeamInterface) {
            throw new \RuntimeException();
        }

        $object->setName($data->name);

        return $object;
    }
}
