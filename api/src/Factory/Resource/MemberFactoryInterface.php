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

namespace App\Factory\Resource;

use App\Model\MemberInterface;
use App\Model\TeamInterface;
use App\Model\UserInterface;

interface MemberFactoryInterface extends ResourceFactoryInterface
{
    public function create(UserInterface $user, TeamInterface $team, bool $owner): MemberInterface;
}
