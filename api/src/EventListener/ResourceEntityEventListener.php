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

namespace App\EventListener;

use App\Event\ResourceWasCreatedEvent;
use App\Http\Provider\CurrentUserProviderInterface;
use App\Model\ResourceInterface;
use App\Model\UserResourceInterface;
use Carbon\CarbonImmutable;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsDoctrineListener(Events::prePersist)]
final readonly class ResourceEntityEventListener
{
    public function __construct(private CurrentUserProviderInterface $currentUserProvider)
    {
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $object = $args->getObject();

        if (!$object instanceof ResourceInterface) {
            return;
        }

        $this->initializeResource($object);
    }

    #[AsEventListener(event: ResourceWasCreatedEvent::class)]
    public function onResourceWasCreated(ResourceWasCreatedEvent $event): void
    {
        $this->initializeResource($event->getResource());
    }

    private function initializeResource(ResourceInterface $resource): void
    {
        if (!$resource->getCreatedAt()) {
            $resource->setCreatedAt(CarbonImmutable::now());
        }

        if ($resource instanceof UserResourceInterface && !$resource->getCreatedBy()) {
            $resource->setCreatedBy($this->currentUserProvider->getCurrentUser());
        }
    }
}
