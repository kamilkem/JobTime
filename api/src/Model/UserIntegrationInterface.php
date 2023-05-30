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

namespace App\Model;

interface UserIntegrationInterface extends IntegrationInterface
{
    public function getUser(): UserInterface;

    public function setUser(UserInterface $user, bool $updateRelation = true): void;

    public function getSecret(): string;

    public function setSecret(string $secret): void;
}
