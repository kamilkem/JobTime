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

namespace App\Security;

use App\Model\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

use function in_array;

class UserVoter extends Voter
{
    public const string IS_USER_INSTANCE = 'user_is_user_instance';

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (
            !in_array($attribute, [
                self::IS_USER_INSTANCE,
            ])
        ) {
            return false;
        }

        return $subject instanceof UserInterface;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface || !$subject instanceof UserInterface) {
            return false;
        }

        return match ($attribute) {
            self::IS_USER_INSTANCE => $user === $subject,
            default => throw new \RuntimeException()
        };
    }
}
