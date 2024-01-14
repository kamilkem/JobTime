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

namespace App\Event;

use App\Model\ResourceInterface;
use Symfony\Contracts\EventDispatcher\Event;

final class ResourceWasCreatedEvent extends Event
{
    public function __construct(private readonly ResourceInterface $resource)
    {
    }

    public function getResource(): ResourceInterface
    {
        return $this->resource;
    }
}
