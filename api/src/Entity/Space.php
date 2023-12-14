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
use App\Security\TeamVoter;
use App\State\TeamSubresourceCollectionProvider;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

#[API\ApiResource(
    uriTemplate: '/spaces/{space}.{_format}',
    operations: [
        new API\Get(
            security: 'is_granted(\'' . TeamVoter::IS_USER_MEMBER . '\', object.getTeam())',
        ),
        new API\Patch(
            security: 'is_granted(\'' . TeamVoter::IS_USER_OWNER . '\', object.getTeam())',
        ),
        new API\Delete(
            security: 'is_granted(\'' . TeamVoter::IS_USER_OWNER . '\', object.getTeam())',
        )
    ],
    uriVariables: [
        'space' => new API\Link(fromClass: self::class),
    ],
    normalizationContext: [
        AbstractNormalizer::GROUPS => [self::GROUP_READ]
    ],
    denormalizationContext: [
        AbstractNormalizer::GROUPS => [self::GROUP_WRITE]
    ],
)]
#[API\ApiResource(
    uriTemplate: '/teams/{team}/spaces.{_format}',
    operations: [
        new API\GetCollection(
            provider: TeamSubresourceCollectionProvider::class,
        ),
    ],
    uriVariables: [
        'team' => new API\Link(toProperty: 'team', fromClass: Team::class)
    ],
    normalizationContext: [
        AbstractNormalizer::GROUPS => [self::GROUP_READ]
    ],
    denormalizationContext: [
        AbstractNormalizer::GROUPS => [self::GROUP_WRITE]
    ],
)]
#[ORM\Entity]
final class Space implements SpaceInterface
{
    use UserResourceTrait;
    use NameTrait;
    use DescriptionTrait;

    public const GROUP_READ = 'space:read';
    public const GROUP_WRITE = 'space:write';

    #[ORM\ManyToOne(targetEntity: Team::class, cascade: ['persist'], inversedBy: 'spaces')]
    #[Groups(groups: [self::GROUP_READ])]
    private TeamInterface $team;

    /**
     * @var Collection<DirectoryInterface>
     */
    #[ORM\OneToMany(
        mappedBy: 'space',
        targetEntity: Directory::class,
        cascade: [
            'persist',
            'remove'
        ],
        orphanRemoval: true
    )]
    #[Groups(groups: [self::GROUP_READ])]
    private Collection $directories;

    public function __construct()
    {
        $this->directories = new ArrayCollection();
    }

    public function getTeam(): TeamInterface
    {
        return $this->team;
    }

    public function setTeam(TeamInterface $team, bool $updateRelation = true): void
    {
        $this->team = $team;

        if ($updateRelation) {
            $team->addSpace($this, false);
        }
    }

    /**
     * @return Collection<DirectoryInterface>
     */
    public function getDirectories(): Collection
    {
        return $this->directories;
    }

    public function addDirectory(DirectoryInterface $directory, bool $updateRelation = true): void
    {
        if ($this->directories->contains($directory)) {
            return;
        }

        $this->directories->add($directory);
        if ($updateRelation) {
            $directory->setSpace($this, false);
        }
    }

    public function removeDirectory(DirectoryInterface $directory): void
    {
        $this->directories->removeElement($directory);
    }
}
