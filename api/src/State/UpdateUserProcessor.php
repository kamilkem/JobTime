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
use App\Dto\UserDto;
use App\Model\UserInterface;

readonly class UpdateUserProcessor extends AbstractUpdateProcessor
{
    protected function prepare(mixed $data, Operation $operation, object $object): UserInterface
    {
        if (!$data instanceof UserDto || !$object instanceof UserInterface) {
            throw new \RuntimeException();
        }

        $object->setFirstName($data->firstName);
        $object->setLastName($data->lastName);

        return $object;
    }
}
