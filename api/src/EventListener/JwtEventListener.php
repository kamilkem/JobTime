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

use App\Model\UserInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final readonly class JwtEventListener
{
    #[AsEventListener('lexik_jwt_authentication.on_jwt_created')]
    public function onJWTCreated(JWTCreatedEvent $event): void
    {
        $user = $event->getUser();

        if (!$user instanceof UserInterface) {
            return;
        }

        $payload = $event->getData();
        $payload['email'] = $user->getEmail();
        $payload['firstName'] = $user->getFirstName();
        $payload['lastName'] = $user->getLastName();

        $event->setData($payload);
    }
}
