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

use App\Model\OrganizationInterface;
use App\Model\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

use function in_array;

class OrganizationVoter extends Voter
{
    public const IS_USER_OWNER = 'is_user_owner';
    public const IS_USER_MEMBER = 'is_user_member';

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (
            !in_array($attribute, [
            self::IS_USER_OWNER,
            self::IS_USER_MEMBER,
            ])
        ) {
            return false;
        }

        return $subject instanceof OrganizationInterface;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface || !$subject instanceof OrganizationInterface) {
            throw new \RuntimeException();
        }


        return match ($attribute) {
            self::IS_USER_OWNER => $subject->isUserOwner($user),
            self::IS_USER_MEMBER => $subject->isUserMember($user),
            default => throw new \RuntimeException()
        };
    }
}
