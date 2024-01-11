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

namespace App\Tests\Behat;

use App\Repository\UserRepositoryInterface;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\Environment\InitializedContextEnvironment;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Hook\BeforeScenario;
use Behat\Step\Given;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Ubirak\RestApiBehatExtension\RestApiContext;

class AuthContext implements Context
{
    private RestApiContext $restApiContext;

    public function __construct(
        private readonly JWTTokenManagerInterface $JWTTokenManager,
        private readonly UserRepositoryInterface $userRepository,
    ) {
    }

    #[BeforeScenario]
    public function gatherContexts(BeforeScenarioScope $scope): void
    {
        /** @var InitializedContextEnvironment $environment */
        $environment = $scope->getEnvironment();
        $environment->registerContext($this);

        $this->restApiContext = $environment->getContext(RestApiContext::class);
    }

    #[Given(pattern: '/^I am a (\S+)$/')]
    public function iAmA(string $email): void
    {
        $user = $this->userRepository->findOneBy(['email' => $email]);

        if ($user) {
            $token = $this->JWTTokenManager->create($user);
            $this->restApiContext->iAddHeaderEqualTo('Authorization', 'Bearer ' . $token);
        }
    }
}
