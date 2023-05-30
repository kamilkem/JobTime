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

use App\Entity\User;
use Doctrine\ORM\Mapping\ManyToOne;

trait CreatedByUserTrait
{
    #[ManyToOne(targetEntity: User::class)]
    protected UserInterface $createdBy;

    public function getCreatedBy(): UserInterface
    {
        return $this->createdBy;
    }

    public function setCreatedBy(UserInterface $createdBy): void
    {
        $this->createdBy = $createdBy;
    }
}
