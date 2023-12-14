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
use ApiPlatform\State\ProcessorInterface;
use Doctrine\ORM\EntityManagerInterface;

use function is_object;

abstract readonly class AbstractCreateProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        $data = $this->prepare($data, $operation);

        if (is_object($data)) {
            $this->entityManager->persist($data);
        }

        $this->entityManager->flush();

        return $data;
    }

    abstract protected function prepare(mixed $data, Operation $operation): mixed;
}
