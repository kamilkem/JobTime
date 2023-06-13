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

use Carbon\CarbonInterface;

interface OrganizationInvitationInterface extends UserResourceInterface
{
    public function getStatus(): OrganizationInvitationStatusEnum;

    public function getInvitationEmail(): ?string;

    public function setInvitationEmail(?string $invitationEmail): void;

    public function getOrganization(): ?OrganizationInterface;

    public function setOrganization(?OrganizationInterface $organization, bool $updateRelation = true): void;

    public function getUser(): ?UserInterface;

    public function setUser(?UserInterface $user, bool $updateRelation = true): void;

    public function getAcceptedAt(): ?CarbonInterface;

    public function setAcceptedAt(?CarbonInterface $acceptedAt): void;

    public function getCanceledAt(): ?CarbonInterface;

    public function setCanceledAt(?CarbonInterface $canceledAt): void;
}
