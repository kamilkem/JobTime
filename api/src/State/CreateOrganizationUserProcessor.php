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
use App\Dto\CreateOrganizationUserInput;
use App\Entity\OrganizationUser;
use App\Model\OrganizationUserInterface;
use App\Repository\OrganizationRepository;
use App\Repository\UserRepository;
use App\Security\OrganizationVoter;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

readonly class CreateOrganizationUserProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: 'ApiPlatform\Doctrine\Common\State\PersistProcessor')]
        private ProcessorInterface $processor,
        private Security $security,
        private OrganizationRepository $organizationRepository,
        private UserRepository $userRepository,
    ) {
    }

    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = []
    ): OrganizationUserInterface {
        if (!$data instanceof CreateOrganizationUserInput || !isset($uriVariables['organizationId'])) {
            throw new \RuntimeException();
        }

        $organization = $this->organizationRepository->find($uriVariables['organizationId']);

        if (!$organization) {
            throw new NotFoundHttpException();
        }

        if (!$this->security->isGranted(OrganizationVoter::IS_USER_OWNER, $organization)) {
            throw new AccessDeniedException();
        }

        $user = $this->userRepository->findOneBy(['email' => $data->email]);

        if (!$user) {
            throw new BadRequestHttpException();
        }

        $newData = new OrganizationUser();
        $newData->setUser($user);
        $newData->setOrganization($organization);
        $newData->setOwner($data->owner);

        return $this->processor->process($newData, $operation, $uriVariables, $context);
    }
}
