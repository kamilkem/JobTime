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

interface InvitationInterface extends UserResourceInterface
{
    public const string GROUP_READ = 'invitation:read';
    public const string GROUP_WRITE = 'invitation:write';

    public const array AGGREGATE_READ_GROUPS = [self::GROUP_READ, ResourceInterface::GROUP_READ];
    public const array AGGREGATE_WRITE_GROUPS = [self::GROUP_WRITE, ResourceInterface::GROUP_WRITE];

    public function getStatus(): InvitationStatusEnum;

    public function getInvitationEmail(): string;

    public function setInvitationEmail(string $invitationEmail): void;

    public function getTeam(): TeamInterface;

    public function setTeam(TeamInterface $team, bool $updateRelation = true): void;

    public function getUser(): ?UserInterface;

    public function setUser(?UserInterface $user, bool $updateRelation = true): void;

    public function getAcceptedAt(): ?CarbonInterface;

    public function setAcceptedAt(?CarbonInterface $acceptedAt): void;

    public function getCanceledAt(): ?CarbonInterface;

    public function setCanceledAt(?CarbonInterface $canceledAt): void;
}
