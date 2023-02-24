<?php

/**
 * This file is part of the jobtime-backend package.
 *
 * (c) Kamil Kozaczyński <kozaczynski.kamil@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Model\IdentifiableTrait;
use App\Model\ProjectGroupInterface;
use App\Model\ProjectInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\Entity]
class ProjectGroup implements ProjectGroupInterface
{
    use IdentifiableTrait;

    /**
     * @var Collection<ProjectInterface>
     */
    #[ORM\OneToMany(mappedBy: 'projectGroups', targetEntity: Project::class)]
    private Collection $projects;

    public function __construct(
        #[ORM\Column]
        private string $name
    ) {
        $this->projects = new ArrayCollection();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return Collection<ProjectInterface>
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(ProjectInterface $project): void
    {
        $this->projects->add($project);
    }

    public function removeProject(ProjectInterface $project): void
    {
        $this->projects->removeElement($project);
    }
}
