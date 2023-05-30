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

interface IntegrationInterface extends ResourceInterface
{
    public function getServiceName(): IntegrationServiceEnum;

    public function setServiceName(IntegrationServiceEnum $serviceName): void;

    public function getStatus(): IntegrationStatusEnum;

    public function setStatus(IntegrationStatusEnum $status): void;
}
