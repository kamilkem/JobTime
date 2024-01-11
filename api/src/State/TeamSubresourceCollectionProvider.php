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

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\TeamRepositoryInterface;
use App\Security\TeamVoter;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

readonly class TeamSubresourceCollectionProvider implements ProviderInterface
{
    public function __construct(
        #[Autowire(service: 'ApiPlatform\Doctrine\Orm\State\CollectionProvider')]
        private ProviderInterface $provider,
        private Security $security,
        private TeamRepositoryInterface $teamRepository
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if (!isset($uriVariables['team'])) {
            throw new \RuntimeException();
        }

        $team = $this->teamRepository->find($uriVariables['team']);

        if (!$team) {
            throw new NotFoundHttpException();
        }

        if (!$this->security->isGranted(TeamVoter::IS_USER_MEMBER, $team)) {
            throw new AccessDeniedException();
        }

        return $this->provider->provide($operation, $uriVariables, $context);
    }
}
