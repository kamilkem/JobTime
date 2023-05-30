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
use App\Dto\CreateUserIntegrationInput;
use App\Entity\UserIntegration;
use App\Model\IntegrationStatusEnum;
use App\Model\UserIntegrationInterface;
use App\Model\UserInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

readonly class CreateUserIntegrationProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: 'ApiPlatform\Doctrine\Common\State\PersistProcessor')]
        private ProcessorInterface $processor,
        private Security $security,
    ) {
    }

    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = []
    ): UserIntegrationInterface {
        $user = $this->security->getUser();

        if (!$user instanceof UserInterface || !$data instanceof CreateUserIntegrationInput) {
            throw new \RuntimeException();
        }

        $newData = new UserIntegration(
            $user,
            $data->secret,
            $data->serviceName,
            IntegrationStatusEnum::ACTIVE
        );

        return $this->processor->process($newData, $operation, $uriVariables, $context);
    }
}
