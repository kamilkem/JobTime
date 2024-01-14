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

use App\Model\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;

class UserInput implements InitializableDtoInterface
{
    #[Groups([UserInterface::GROUP_WRITE])]
    public string $firstName;

    #[Groups([UserInterface::GROUP_WRITE])]
    public string $lastName;

    public static function initialize(object $fromObject): self
    {
        if (!$fromObject instanceof UserInterface) {
            throw new \RuntimeException();
        }

        $object = new self();
        $object->firstName = $fromObject->getFirstName();
        $object->lastName = $fromObject->getLastName();

        return $object;
    }
}
