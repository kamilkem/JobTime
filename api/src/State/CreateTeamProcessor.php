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
use App\Dto\TeamDto;
use App\Entity\Member;
use App\Entity\Team;
use App\Provider\CurrentUserProviderInterface;
use Doctrine\ORM\EntityManagerInterface;

readonly class CreateTeamProcessor extends AbstractCreateProcessor
{
    public function __construct(
        EntityManagerInterface $entityManager,
        private CurrentUserProviderInterface $currentUserProvider
    ) {
        parent::__construct($entityManager);
    }

    protected function prepare(mixed $data, Operation $operation): Team
    {
        if (!$data instanceof TeamDto) {
            throw new \RuntimeException();
        }

        $user = $this->currentUserProvider->getCurrentUser();
        $team = new Team($data->name);
        $member = new Member(
            $user,
            $team,
            true,
        );

        $user->addMember($member, false);
        $team->addMember($member, false);

        return $team;
    }
}
