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

use ApiPlatform\Metadata as API;
use App\Entity\Team;
use App\Model\SpaceInterface;
use App\Model\TeamInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class SpaceInput implements InitializableDtoInterface
{
    #[API\ApiProperty(
        example: '/teams/{team}',
    )]
    #[Groups([SpaceInterface::GROUP_WRITE])]
    public Team|TeamInterface $team;

    #[Assert\Type(type: ['string'])]
    #[Groups([SpaceInterface::GROUP_WRITE])]
    public string $name;

    #[Assert\Type(type: ['string', 'null'])]
    #[Groups([SpaceInterface::GROUP_WRITE])]
    public ?string $description = null;

    public static function initialize(object $fromObject): InitializableDtoInterface
    {
        if (!$fromObject instanceof SpaceInterface) {
            throw new \RuntimeException();
        }

        $object = new self();
        $object->name = $fromObject->getName();
        $object->team = $fromObject->getTeam();
        $object->description = $fromObject->getDescription();

        return $object;
    }
}
