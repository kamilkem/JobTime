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
use App\Entity\OrganizationMember;
use App\Model\OrganizationInterface;
use App\Model\UserInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\DependencyInjection\Attribute\Target;

readonly class CreateOrganizationProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: 'ApiPlatform\Doctrine\Common\State\PersistProcessor')]
        private ProcessorInterface $processor,
        private Security $security
    ) {
    }

    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = []
    ): OrganizationInterface {
        $user = $this->security->getUser();

        if (!$user instanceof UserInterface || !$data instanceof OrganizationInterface) {
            throw new \RuntimeException();
        }

        $organizationMember = new OrganizationMember($user, $data, true);
        $data->addMember($organizationMember, false);

        return $this->processor->process($data, $operation, $uriVariables, $context);
    }
}
