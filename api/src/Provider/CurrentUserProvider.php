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

namespace App\Provider;

use App\Model\UserInterface;
use Symfony\Bundle\SecurityBundle\Security;

final readonly class CurrentUserProvider implements CurrentUserProviderInterface
{
    public function __construct(private Security $security)
    {
    }

    public function getCurrentUser(): UserInterface
    {
        $user = $this->security->getUser();

        if (!$user instanceof UserInterface) {
            throw new \RuntimeException();
        }

        return $user;
    }
}
