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
use ApiPlatform\Metadata\Patch;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\InitializableDtoInterface;
use App\Model\IdentifiableInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

use function get_class;
use function is_a;
use function is_object;
use function method_exists;

abstract readonly class AbstractUpdateProcessor implements ProcessorInterface, InitializableProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @throws ORMException
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        $object = $context['previous_data'] ?? null;
        if (!is_object($object) || !method_exists($object, 'getId')) {
            throw new \RuntimeException();
        }

        /* @phpstan-ignore-next-line */
        $entity = $this->entityManager->getReference(get_class($object), $object->getId());
        if (!is_object($entity)) {
            throw new \RuntimeException();
        }
        $data = $this->prepare($data, $operation, $entity);

        $this->entityManager->flush();

        return $data;
    }

    public function initialize(
        mixed $data,
        string $class,
        ?string $format = null,
        array $context = []
    ): InitializableDtoInterface {
        $operation = $context['operation'];

        $isEligible = true;

        if (!$operation instanceof Patch) {
            $isEligible = false;
        }

        $input = $operation->getInput()['class'];

        if ($isEligible && (!$input || !is_a($input, InitializableDtoInterface::class, true))) {
            $isEligible = false;
        }

        if (!$isEligible) {
            throw new \RuntimeException();
        }

        return $input::initialize($context[AbstractNormalizer::OBJECT_TO_POPULATE]);
    }

    abstract protected function prepare(mixed $data, Operation $operation, object $object): mixed;
}
