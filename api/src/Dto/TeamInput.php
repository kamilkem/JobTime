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

use App\Entity\Team;
use App\Model\TeamInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class TeamInput implements InitializableDtoInterface
{
    #[Assert\Type(type: ['string'])]
    #[Groups([Team::GROUP_WRITE])]
    public string $name;

    public static function initialize(object $fromObject): self
    {
        if (!$fromObject instanceof TeamInterface) {
            throw new \RuntimeException();
        }

        $object = new self();
        $object->name = $fromObject->getName();

        return $object;
    }
}
