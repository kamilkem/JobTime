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
use App\Model\ProjectInterface;
use App\Repository\OrganizationRepository;
use App\Security\OrganizationVoter;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

readonly class CreateProjectProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: 'ApiPlatform\Doctrine\Common\State\PersistProcessor')]
        private ProcessorInterface $processor,
        private Security $security,
        private OrganizationRepository $organizationRepository,
    ) {
    }

    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = []
    ): ProjectInterface {
        if (!$data instanceof ProjectInterface || !isset($uriVariables['organizationId'])) {
            throw new \RuntimeException();
        }

        $organization = $this->organizationRepository->find($uriVariables['organizationId']);

        if (!$organization) {
            throw new NotFoundHttpException();
        }

        if (!$this->security->isGranted(OrganizationVoter::IS_USER_OWNER, $organization)) {
            throw new AccessDeniedException();
        }

        $data->setOrganization($organization);

        return $this->processor->process($data, $operation, $uriVariables, $context);
    }
}
