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

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Model\CreatedAtTrait;
use App\Model\CreatedByUserTrait;
use App\Model\IdentifiableTrait;
use App\Model\ProjectGroupInterface;
use App\Model\ProjectInterface;
use Carbon\CarbonImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\Entity]
class ProjectGroup implements ProjectGroupInterface
{
    use IdentifiableTrait;
    use CreatedAtTrait;
    use CreatedByUserTrait;

    /**
     * @var Collection<ProjectInterface>
     */
    #[ORM\OneToMany(mappedBy: 'group', targetEntity: Project::class, cascade: ['persist'])]
    private Collection $projects;

    public function __construct(
        #[ORM\Column]
        private string $name
    ) {
        $this->createdAt = CarbonImmutable::now();
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

    public function addProject(ProjectInterface $project, bool $updateRelation = true): void
    {
        if ($this->projects->contains($project)) {
            return;
        }

        $this->projects->add($project);
        if ($updateRelation) {
            $project->setGroup($this, false);
        }
    }

    public function removeProject(ProjectInterface $project, bool $updateRelation = true): void
    {
        $this->projects->removeElement($project);
        if ($updateRelation) {
            $project->setGroup(null, false);
        }
    }
}
