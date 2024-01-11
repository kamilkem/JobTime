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

use App\Entity\Member;
use App\Model\MemberInterface;
use App\Model\TeamInterface;
use App\Model\UserInterface;

class MemberFactory implements MemberFactoryInterface
{
    public function create(UserInterface $user, TeamInterface $team, bool $owner): MemberInterface
    {
        $member = new Member(
            $user,
            $team,
            $owner
        );

        $user->addMember($member, false);
        $team->addMember($member, false);

        return $member;
    }
}
