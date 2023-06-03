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

namespace App\Entity;

use ApiPlatform\Metadata as API;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Dto\CreateUserIntegrationInput;
use App\Model\IntegrationServiceEnum;
use App\Model\IntegrationStatusEnum;
use App\Model\UserIntegrationInterface;
use App\Model\UserInterface;
use App\State\CreateUserIntegrationProcessor;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[API\ApiResource(
    operations: [
        new GetCollection(),
        new Post(
            input: CreateUserIntegrationInput::class,
            processor: CreateUserIntegrationProcessor::class,
        ),
        new Delete(),
    ]
)]
#[ORM\Entity]
class UserIntegration extends AbstractIntegration implements UserIntegrationInterface
{
    public function __construct(
        #[ORM\ManyToOne(targetEntity: User::class, cascade: ['persist'], inversedBy: 'integrations')]
        private UserInterface $user,
        #[ORM\Column(type: 'string')]
        private string $secret,
        IntegrationServiceEnum $serviceName,
        IntegrationStatusEnum $status,
    ) {
        parent::__construct($serviceName, $status);
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function setUser(UserInterface $user, bool $updateRelation = true): void
    {
        $this->user = $user;

        if ($updateRelation) {
            $user->addIntegration($this, false);
        }
    }

    public function getSecret(): string
    {
        return $this->secret;
    }

    public function setSecret(string $secret): void
    {
        $this->secret = $secret;
    }
}
