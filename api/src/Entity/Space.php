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
use App\Dto\SpaceInput;
use App\Model\DescriptionTrait;
use App\Model\DirectoryInterface;
use App\Model\NameTrait;
use App\Model\SpaceInterface;
use App\Model\TeamInterface;
use App\Model\UserResourceTrait;
use App\Security\TeamVoter;
use App\State\CreateSpaceProcessor;
use App\State\TeamSubresourceCollectionProvider;
use App\State\UpdateSpaceProcessor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

#[API\ApiResource(
    uriTemplate: '/spaces.{_format}',
    operations: [
        new API\Post(
            securityPostDenormalize: 'is_granted(\'' . TeamVoter::IS_USER_MEMBER . '\', object.team)',
            input: SpaceInput::class,
            processor: CreateSpaceProcessor::class,
        ),
    ],
    normalizationContext: [
        AbstractNormalizer::GROUPS => self::AGGREGATE_READ_GROUPS,
    ],
    denormalizationContext: [
        AbstractNormalizer::GROUPS => self::AGGREGATE_WRITE_GROUPS,
    ],
)]
#[API\ApiResource(
    uriTemplate: '/spaces/{space}.{_format}',
    operations: [
        new API\Get(
            security: 'is_granted(\'' . TeamVoter::IS_USER_MEMBER . '\', object.getTeam())',
        ),
        new API\Patch(
            security: 'is_granted(\'' . TeamVoter::IS_USER_MEMBER . '\', object.getTeam())',
            input: SpaceInput::class,
            processor: UpdateSpaceProcessor::class,
        ),
        new API\Delete(
            security: 'is_granted(\'' . TeamVoter::IS_USER_MEMBER . '\', object.getTeam())',
        )
    ],
    uriVariables: [
        'space' => new API\Link(fromClass: self::class),
    ],
    normalizationContext: [
        AbstractNormalizer::GROUPS => self::AGGREGATE_READ_GROUPS,
    ],
    denormalizationContext: [
        AbstractNormalizer::GROUPS => self::AGGREGATE_WRITE_GROUPS,
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
        AbstractNormalizer::GROUPS => self::AGGREGATE_READ_GROUPS,
    ],
    denormalizationContext: [
        AbstractNormalizer::GROUPS => self::AGGREGATE_WRITE_GROUPS,
    ],
)]
#[ORM\Entity]
class Space implements SpaceInterface
{
    use UserResourceTrait;
    use NameTrait;
    use DescriptionTrait;

    #[ORM\ManyToOne(targetEntity: Team::class, cascade: ['persist'], inversedBy: 'spaces')]
    #[ORM\JoinColumn(nullable: false)]
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

    public function __construct(
        TeamInterface $team,
        string $name,
        ?string $description = null,
        ?UuidInterface $id = null,
    ) {
        $this->team = $team;
        $this->name = $name;
        $this->description = $description;
        $this->id = $id ?? Uuid::uuid4();

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
