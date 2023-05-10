<?php

/**
 * This file is part of the JobTime package.
 *
 * (c) Kamil Kozaczyński <kozaczynski.kamil@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\EventListener;

use App\Model\UserInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final readonly class UpdateUserPasswordEventListener
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $this->hashPassword($args);
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $this->hashPassword($args);
    }

    private function hashPassword(LifecycleEventArgs $args): void
    {
        $user = $args->getObject();

        if (!$user instanceof UserInterface || !$user->getPlainPassword()) {
            return;
        }

        $user->setPassword(
            $this->passwordHasher->hashPassword($user, $user->getPlainPassword())
        );

        $user->eraseCredentials();
    }
}
