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
use App\Entity\OrganizationMember;
use Carbon\CarbonInterface;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface as BaseUserInterface;

interface UserInterface extends
    ResourceInterface,
    BaseUserInterface,
    PasswordAuthenticatedUserInterface
{
    public const ROLE_USER = 'ROLE_USER';

    public function getEmail(): string;

    public function setEmail(string $email): void;

    public function getPassword(): ?string;

    public function setPassword(?string $password): void;

    public function getRoles(): array;

    public function getFirstName(): ?string;

    public function setFirstName(?string $firstName): void;

    public function getLastName(): ?string;

    public function setLastName(?string $lastName): void;

    public function getBirthDate(): ?CarbonInterface;

    public function setBirthDate(?CarbonInterface $birthDate): void;

    public function isConfirmed(): bool;

    public function setConfirmed(bool $confirmed): void;

    public function getPlainPassword(): ?string;

    public function setPlainPassword(?string $plainPassword): void;

    /**
     * @return Collection<OrganizationMemberInterface>
     */
    public function getOrganizationMembers(): Collection;

    public function addOrganizationMember(OrganizationMember $organizationMember, bool $updateRelation = true): void;

    public function removeOrganizationMember(OrganizationMember $organizationMember): void;

    /**
     * @return Collection<OrganizationInvitationInterface>
     */
    public function getOrganizationInvitations(): Collection;

    public function addOrganizationInvitation(
        OrganizationInvitationInterface $organizationInvitation,
        bool $updateRelation = true
    ): void;

    public function removeOrganizationInvitation(OrganizationInvitation $organizationInvitation): void;

    /**
     * @return Collection<UserIntegrationInterface>
     */
    public function getIntegrations(): Collection;

    public function addIntegration(UserIntegrationInterface $integration, bool $updateRelation = true): void;

    public function removeIntegration(UserIntegrationInterface $integration): void;
}
