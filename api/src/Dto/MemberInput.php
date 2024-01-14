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

namespace App\Dto;

use App\Model\MemberInterface;
use Symfony\Component\Serializer\Attribute\Groups;

class MemberInput implements InitializableDtoInterface
{
    #[Groups([MemberInterface::GROUP_WRITE])]
    public bool $owner;

    public static function initialize(object $fromObject): InitializableDtoInterface
    {
        if (!$fromObject instanceof MemberInterface) {
            throw new \RuntimeException();
        }

        $object = new self();
        $object->owner = $fromObject->isOwner();

        return $object;
    }
}
