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
use App\Dto\MemberInput;
use App\Model\MemberInterface;

readonly class UpdateMemberProcessor extends AbstractUpdateProcessor
{
    protected function prepare(mixed $data, Operation $operation, object $object): MemberInterface
    {
        if (!$data instanceof MemberInput || !$object instanceof MemberInterface) {
            throw new \RuntimeException();
        }

        $object->setOwner($data->owner);

        return $object;
    }
}
