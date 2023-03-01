<?php

/**
 * This file is part of the JobTime package.
 *
 * (c) Kamil Kozaczyński <kozaczynski.kamil@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Model;

use Carbon\CarbonInterface;
use Doctrine\ORM\Mapping\Column;

trait CreatedAtTrait
{
    #[Column(type: 'carbon_immutable')]
    private CarbonInterface $createdAt;

    public function getCreatedAt(): CarbonInterface
    {
        return $this->createdAt;
    }
}
