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
use App\Entity\Organization;
use App\Entity\Task;
use App\Entity\UserIntegration;
use App\Model\UserInterface;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\SecurityBundle\Security;

use function explode;
use function sprintf;

readonly class AuthenticatedUserExtension implements QueryCollectionExtensionInterface
{
    public function __construct(private Security $security)
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

        $user = $this->security->getUser();

        if (!$user instanceof UserInterface) {
            throw new \RuntimeException();
        }

        $rootAlias = $queryBuilder->getRootAliases()[0];
        match ($resourceClass) {
            Organization::class => $this->applyForOrganization($rootAlias, $queryBuilder, $user),
            UserIntegration::class => $this->applyForUserIntegration($rootAlias, $queryBuilder, $user),
            Task::class => $this->applyForTask($rootAlias, $queryBuilder, $user),
            default => throw new \RuntimeException()
        };
    }

    private function applyForOrganization(
        string $rootAlias,
        QueryBuilder $queryBuilder,
        UserInterface $user
    ): void {
        $queryBuilder
            ->join(sprintf('%s.members', $rootAlias), '_member')
            ->andWhere($queryBuilder->expr()->eq('_member.user', ':user'))
            ->setParameter('user', $user);
    }

    private function applyForUserIntegration(
        string $rootAlias,
        QueryBuilder $queryBuilder,
        UserInterface $user
    ): void {
        $queryBuilder
            ->andWhere($queryBuilder->expr()->eq(sprintf('%s.user', $rootAlias), ':user'))
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
