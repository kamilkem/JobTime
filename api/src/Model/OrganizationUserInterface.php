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

interface OrganizationUserInterface extends IdentifiableInterface, CreatedAtInterface
{
    public function getUser(): UserInterface;

    public function setUser(UserInterface $user, bool $updateRelation = true): void;

    public function getOrganization(): OrganizationInterface;

    public function setOrganization(OrganizationInterface $organization, bool $updateRelation = true): void;

    public function isOwner(): bool;

    public function setOwner(bool $owner): void;
}
