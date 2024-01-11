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

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Gesdinet\JWTRefreshTokenBundle\Entity\RefreshTokenRepository;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: RefreshTokenRepository::class)]
class RefreshToken implements RefreshTokenInterface
{
    #[ORM\Column(type: 'integer', unique: true), ORM\Id, ORM\GeneratedValue(strategy: 'AUTO')]
    protected int|string|null $id;

    #[ORM\Column(type: 'string', length: 128, unique: true)]
    protected string|null $refreshToken;

    #[ORM\Column(type: 'string', length: 255)]
    protected string|null $username;

    #[ORM\Column(type: 'datetimetz')]
    protected \DateTimeInterface|null $valid;

    /**
     * Creates a new model instance based on the provided details.
     */
    public static function createForUserWithTtl(string $refreshToken, UserInterface $user, int $ttl): RefreshTokenInterface
    {
        $valid = new \DateTime();
        $valid->modify('+' . $ttl . ' seconds');

        $model = new self();
        $model->setRefreshToken($refreshToken);
        $model->setUsername($user->getUserIdentifier());
        $model->setValid($valid);

        return $model;
    }

    /**
     * @return string Refresh Token
     */
    public function __toString(): string
    {
        return $this->getRefreshToken() ?: '';
    }

    /**
     * {@inheritdoc}
     */
    public function getId(): int|string|null
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function setRefreshToken($refreshToken = null): RefreshTokenInterface
    {
        if (null === $refreshToken || '' === $refreshToken) {
            trigger_deprecation('gesdinet/jwt-refresh-token-bundle', '1.0', 'Passing an empty token to %s() to automatically generate a token is deprecated.', __METHOD__);

            $refreshToken = bin2hex(random_bytes(64));
        }

        $this->refreshToken = $refreshToken;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRefreshToken(): string|null
    {
        return $this->refreshToken;
    }

    /**
     * {@inheritdoc}
     */
    public function setValid($valid): RefreshTokenInterface
    {
        $this->valid = $valid;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getValid(): DateTimeInterface|null
    {
        return $this->valid;
    }

    /**
     * {@inheritdoc}
     */
    public function setUsername($username): RefreshTokenInterface
    {
        $this->username = $username;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername(): string|null
    {
        return $this->username;
    }

    /**
     * {@inheritdoc}
     */
    public function isValid(): bool
    {
        return null !== $this->valid && $this->valid >= new \DateTime();
    }
}
