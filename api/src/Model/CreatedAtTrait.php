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

namespace App\Model;

use Carbon\CarbonInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

trait CreatedAtTrait
{
    #[ORM\Column(type: 'carbondatetime_immutable')]
    #[Groups(groups: [ResourceInterface::GROUP_READ])]
    protected ?CarbonInterface $createdAt = null;

    public function getCreatedAt(): ?CarbonInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(CarbonInterface $createdAt): void
    {
        if ($this->createdAt) {
            throw new \LogicException();
        }

        $this->createdAt = $createdAt;
    }
}
