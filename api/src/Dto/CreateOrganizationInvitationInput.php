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

namespace App\Dto;

use App\Entity\OrganizationInvitation;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class CreateOrganizationInvitationInput
{
    #[Assert\NotBlank]
    #[Assert\Email]
    #[Groups(groups: [OrganizationInvitation::GROUP_WRITE])]
    public string $email;
}
