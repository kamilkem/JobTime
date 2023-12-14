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

use ApiPlatform\Metadata as API;
use App\Model\DescriptionTrait;
use App\Model\DirectoryInterface;
use App\Model\NameTrait;
use App\Model\SpaceInterface;
use App\Model\TeamInterface;
use App\Model\UserResourceTrait;
use App\Model\ViewInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[API\ApiResource(
    operations: []
)]
#[ORM\Entity]
final class Directory implements DirectoryInterface
{
    use UserResourceTrait;
    use NameTrait;
    use DescriptionTrait;

    public const GROUP_READ = 'directory:read';
    public const GROUP_WRITE = 'directory:write';

    #[ORM\ManyToOne(targetEntity: Space::class, cascade: ['persist'], inversedBy: 'directories')]
    #[Assert\NotNull]
    #[Groups(groups: [self::GROUP_READ])]
    private SpaceInterface $space;

    #[ORM\ManyToOne(targetEntity: self::class, cascade: ['persist'], inversedBy: 'subDirectories')]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(groups: [self::GROUP_READ])]
    private ?DirectoryInterface $directory = null;

    /**
     * @var Collection<ViewInterface>
     */
    #[ORM\OneToMany(
        mappedBy: 'directory',
        targetEntity: View::class,
        cascade: [
            'persist',
            'remove'
        ],
        orphanRemoval: true
    )]
    #[Groups(groups: [self::GROUP_READ])]
    private Collection $views;

    /**
     * @var Collection<DirectoryInterface>
     */
    #[ORM\OneToMany(
        mappedBy: 'directory',
        targetEntity: self::class,
        cascade: [
            'persist',
            'remove'
        ],
        orphanRemoval: true
    )]
    #[Groups(groups: [self::GROUP_READ])]
    private Collection $subDirectories;

    public function __construct()
    {
        $this->views = new ArrayCollection();
        $this->subDirectories = new ArrayCollection();
    }

    public function getTeam(): TeamInterface
    {
        return $this->space->getTeam();
    }

    public function getSpace(): SpaceInterface
    {
        return $this->space;
    }

    public function setSpace(SpaceInterface $space, bool $updateRelation = true): void
    {
        $this->space = $space;

        if ($updateRelation) {
            $space->addDirectory($this, false);
        }
    }

    public function getDirectory(): ?DirectoryInterface
    {
        return $this->directory;
    }

    public function setDirectory(?DirectoryInterface $directory, bool $updateRelation = true): void
    {
        $this->directory = $directory;

        if ($directory && $updateRelation) {
            $directory->addSubDirectory($this, false);
        }
    }

    /**
     * @return Collection<ViewInterface>
     */
    public function getViews(): Collection
    {
        return $this->views;
    }

    public function addView(ViewInterface $view, bool $updateRelation = true): void
    {
        if ($this->views->contains($view)) {
            return;
        }

        $this->views->add($view);
        if ($updateRelation) {
            $view->setDirectory($this, false);
        }
    }

    public function removeView(ViewInterface $view): void
    {
        $this->views->removeElement($view);
    }

    /**
     * @return Collection<DirectoryInterface>
     */
    public function getSubDirectories(): Collection
    {
        return $this->subDirectories;
    }

    public function addSubDirectory(DirectoryInterface $directory, bool $updateRelation = true): void
    {
        if ($this->subDirectories->contains($directory)) {
            return;
        }

        $this->subDirectories->add($directory);
        if ($updateRelation) {
            $directory->setDirectory($this, false);
        }
    }

    public function removeSubDirectory(DirectoryInterface $directory): void
    {
        $this->subDirectories->removeElement($directory);
    }
}
