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

namespace App\Factory\Resource;

use App\Event\ResourceWasCreatedEvent;
use App\Model\ResourceInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

abstract readonly class AbstractResourceFactory implements ResourceFactoryInterface
{
    public function __construct(private EventDispatcherInterface $eventDispatcher)
    {
    }

    protected function dispatchResourceWasCreatedEvent(ResourceInterface $resource): void
    {
        $this->eventDispatcher->dispatch(new ResourceWasCreatedEvent($resource));
    }
}
