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

namespace App\Doctrine;

use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use App\Entity\Task;
use App\Entity\Team;
use App\Http\Provider\CurrentUserProviderInterface;
use App\Model\UserInterface;
use Doctrine\ORM\QueryBuilder;

use function explode;
use function sprintf;

final readonly class AuthenticatedUserExtension implements QueryCollectionExtensionInterface
{
    public function __construct(private CurrentUserProviderInterface $currentUserProvider)
    {
    }

    public function applyToCollection(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        Operation $operation = null,
        array $context = []
    ): void {
        $uri = $context['request_uri'] ?? null;

        if (!$uri || 'user' !== explode('/', $uri)[1]) {
            return;
        }

        $user = $this->currentUserProvider->getCurrentUser();

        $rootAlias = $queryBuilder->getRootAliases()[0];
        match ($resourceClass) {
            Team::class => $this->applyForTeam($rootAlias, $queryBuilder, $user),
            Task::class => $this->applyForTask($rootAlias, $queryBuilder, $user),
            default => throw new \RuntimeException()
        };
    }

    private function applyForTeam(
        string $rootAlias,
        QueryBuilder $queryBuilder,
        UserInterface $user
    ): void {
        $queryBuilder
            ->join(sprintf('%s.members', $rootAlias), '_member')
            ->andWhere($queryBuilder->expr()->eq('_member.user', ':user'))
            ->setParameter('user', $user);
    }

    private function applyForTask(
        string $rootAlias,
        QueryBuilder $queryBuilder,
        UserInterface $user
    ): void {
        $queryBuilder
            ->join(sprintf('%s.assignedUsers', $rootAlias), '_assignedUsers')
            ->andWhere($queryBuilder->expr()->eq('_assignedUsers', ':user'))
            ->setParameter('user', $user);
    }
}
