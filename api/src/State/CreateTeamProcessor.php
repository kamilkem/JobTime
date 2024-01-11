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
use App\Dto\TeamInput;
use App\Factory\Resource\MemberFactoryInterface;
use App\Factory\Resource\TeamFactoryInterface;
use App\Http\Provider\CurrentUserProviderInterface;
use App\Model\TeamInterface;
use Doctrine\ORM\EntityManagerInterface;

readonly class CreateTeamProcessor extends AbstractCreateProcessor
{
    public function __construct(
        EntityManagerInterface $entityManager,
        private CurrentUserProviderInterface $currentUserProvider,
        private TeamFactoryInterface $teamFactory,
        private MemberFactoryInterface $memberFactory,
    ) {
        parent::__construct($entityManager);
    }

    protected function prepare(mixed $data, Operation $operation): TeamInterface
    {
        if (!$data instanceof TeamInput) {
            throw new \RuntimeException();
        }

        $user = $this->currentUserProvider->getCurrentUser();
        $team = $this->teamFactory->create($data->name);

        $this->memberFactory->create($user, $team, true);

        return $team;
    }
}
