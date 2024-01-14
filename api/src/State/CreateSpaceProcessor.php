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
use App\Dto\SpaceInput;
use App\Factory\Resource\SpaceFactoryInterface;
use App\Model\SpaceInterface;
use Doctrine\ORM\EntityManagerInterface;

readonly class CreateSpaceProcessor extends AbstractCreateProcessor
{
    public function __construct(EntityManagerInterface $entityManager, private SpaceFactoryInterface $spaceFactory)
    {
        parent::__construct($entityManager);
    }

    protected function prepare(mixed $data, Operation $operation): SpaceInterface
    {
        if (!$data instanceof SpaceInput) {
            throw new \RuntimeException();
        }

        return $this->spaceFactory->create($data->team, $data->name, $data->description);
    }
}
