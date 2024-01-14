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

use App\Entity\Invitation;
use App\Entity\Member;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface as BaseUserInterface;

interface UserInterface extends
    ResourceInterface,
    BaseUserInterface,
    PasswordAuthenticatedUserInterface
{
    public const string GROUP_READ = 'user:read';
    public const string GROUP_WRITE = 'user:write';

    public const array AGGREGATE_READ_GROUPS = [self::GROUP_READ, ResourceInterface::GROUP_READ];
    public const array AGGREGATE_WRITE_GROUPS = [self::GROUP_WRITE, ResourceInterface::GROUP_WRITE];

    public const string ROLE_USER = 'ROLE_USER';

    public function getEmail(): string;

    public function setEmail(string $email): void;

    public function getPassword(): ?string;

    public function setPassword(?string $password): void;

    public function getRoles(): array;

    public function getFirstName(): string;

    public function setFirstName(string $firstName): void;

    public function getLastName(): string;

    public function setLastName(string $lastName): void;

    public function isConfirmed(): bool;

    public function setConfirmed(bool $confirmed): void;

    public function getPlainPassword(): ?string;

    public function setPlainPassword(?string $plainPassword): void;

    /**
     * @return Collection<MemberInterface>
     */
    public function getMembers(): Collection;

    public function addMember(Member $member, bool $updateRelation = true): void;

    public function removeMember(Member $member): void;

    /**
     * @return Collection<InvitationInterface>
     */
    public function getInvitations(): Collection;

    public function addInvitation(
        InvitationInterface $invitation,
        bool $updateRelation = true
    ): void;

    public function removeInvitation(Invitation $invitation): void;
}
