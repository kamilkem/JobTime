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

use Doctrine\Common\Collections\Collection;

interface OrganizationInterface extends ResourceInterface, OwnableInterface
{
    public function getName(): ?string;

    public function setName(string $name): void;

    /**
     * @return Collection<OrganizationUserInterface>
     */
    public function getOrganizationUsers(): Collection;

    public function addOrganizationUser(OrganizationUserInterface $organizationUser, bool $updateRelation = true): void;

    public function removeOrganizationUser(OrganizationUserInterface $organizationUser): void;

    /**
     * @return Collection<OrganizationUserInterface>
     */
    public function getProjects(): Collection;

    public function addProject(ProjectInterface $project, bool $updateRelation = true): void;

    public function removeProject(ProjectInterface $project): void;

    public function isUserMember(UserInterface $user): bool;
}
