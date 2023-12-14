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
use Doctrine\Common\Collections\Collection;

interface TeamInterface extends ResourceInterface, OwnableInterface, NameInterface
{
    /**
     * @return Collection<MemberInterface>
     */
    public function getMembers(): Collection;

    public function addMember(MemberInterface $member, bool $updateRelation = true): void;

    public function removeMember(MemberInterface $member): void;

    /**
     * @return Collection<InvitationInterface>
     */
    public function getInvitations(): Collection;

    public function removeInvitation(Invitation $invitation): void;

    public function addInvitation(
        InvitationInterface $invitation,
        bool $updateRelation = true
    ): void;

    /**
     * @return Collection<MemberInterface>
     */
    public function getSpaces(): Collection;

    public function addSpace(SpaceInterface $space, bool $updateRelation = true): void;

    public function removeSpace(SpaceInterface $space): void;

    public function isUserMember(UserInterface $user): bool;
}
