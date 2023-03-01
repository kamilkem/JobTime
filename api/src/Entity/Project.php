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
use App\Model\CreatedAtTrait;
use App\Model\CreatedByUserTrait;
use App\Model\IdentifiableTrait;
use App\Model\ProjectGroupInterface;
use App\Model\ProjectInterface;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\Entity]
class Project implements ProjectInterface
{
    use IdentifiableTrait;
    use CreatedAtTrait;
    use CreatedByUserTrait;

    public function __construct(
        #[ORM\Column]
        private string $name,
        #[ORM\ManyToOne(targetEntity: ProjectGroup::class, inversedBy: 'projects')]
        private ?ProjectGroupInterface $projectGroup = null
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getProjectGroup(): ?ProjectGroupInterface
    {
        return $this->projectGroup;
    }

    public function setProjectGroup(?ProjectGroupInterface $projectGroup): void
    {
        $this->projectGroup = $projectGroup;
        $projectGroup->addProject($this);
    }
}
