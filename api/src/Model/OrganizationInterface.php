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

use App\Entity\OrganizationInvitation;
use Doctrine\Common\Collections\Collection;

interface OrganizationInterface extends ResourceInterface, OwnableInterface
{
    public function getName(): ?string;

    public function setName(string $name): void;

    /**
     * @return Collection<OrganizationMemberInterface>
     */
    public function getMembers(): Collection;

    public function addMember(OrganizationMemberInterface $organizationMember, bool $updateRelation = true): void;

    public function removeMember(OrganizationMemberInterface $organizationMember): void;

    /**
     * @return Collection<OrganizationInvitationInterface>
     */
    public function getInvitations(): Collection;

    public function removeInvitation(OrganizationInvitation $organizationInvitation): void;

    public function addInvitation(
        OrganizationInvitationInterface $organizationInvitation,
        bool $updateRelation = true
    ): void;

    /**
     * @return Collection<OrganizationMemberInterface>
     */
    public function getProjects(): Collection;

    public function addProject(ProjectInterface $project, bool $updateRelation = true): void;

    public function removeProject(ProjectInterface $project): void;

    public function isUserMember(UserInterface $user): bool;
}
