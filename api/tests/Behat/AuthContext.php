<?php

namespace App\Tests\Behat;

use App\Repository\UserRepository;
use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Hook\BeforeScenario;
use Behat\Step\Given;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Ubirak\RestApiBehatExtension\RestApiContext;

readonly class AuthContext implements Context
{
    private RestApiContext $restApiContext;

    public function __construct(
        private JWTTokenManagerInterface $JWTTokenManager,
        private UserRepository $userRepository,
    ) {
    }

    #[BeforeScenario]
    public function gatherContexts(BeforeScenarioScope $scope): void
    {
        $environment = $scope->getEnvironment();
        $environment->registerContext($this);

        $this->restApiContext = $environment->getContext(RestApiContext::class);
    }

    #[Given(pattern: '/^I am a (\S+)$/')]
    public function iAmA(string $email): void
    {
        $user = $this->userRepository->findOneBy(['email' => $email]);
        $token = $this->JWTTokenManager->create($user);

        $this->restApiContext->iAddHeaderEqualTo('Authorization', 'Bearer ' . $token);
    }
}
