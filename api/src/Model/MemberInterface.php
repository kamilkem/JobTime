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

interface MemberInterface extends ResourceInterface
{
    public const string GROUP_READ = 'member:read';
    public const string GROUP_WRITE = 'member:write';

    public const array AGGREGATE_READ_GROUPS = [self::GROUP_READ, ResourceInterface::GROUP_READ];
    public const array AGGREGATE_WRITE_GROUPS = [self::GROUP_WRITE, ResourceInterface::GROUP_WRITE];

    public function getUser(): ?UserInterface;

    public function setUser(UserInterface $user, bool $updateRelation = true): void;

    public function getTeam(): ?TeamInterface;

    public function setTeam(TeamInterface $team, bool $updateRelation = true): void;

    public function isOwner(): bool;

    public function setOwner(bool $owner): void;
}
