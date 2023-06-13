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

use App\Model\IntegrationInterface;
use App\Model\IntegrationServiceEnum;
use App\Model\IntegrationStatusEnum;
use App\Model\ResourceTrait;
use Doctrine\ORM\Mapping as ORM;

#[ORM\MappedSuperclass]
abstract class AbstractIntegration implements IntegrationInterface
{
    use ResourceTrait;

    #[ORM\Column(type: 'string', enumType: IntegrationServiceEnum::class)]
    private IntegrationServiceEnum $serviceName;

    #[ORM\Column(type: 'string', enumType: IntegrationStatusEnum::class)]
    private IntegrationStatusEnum $status;

    public function __construct(
        IntegrationServiceEnum $serviceName,
        IntegrationStatusEnum $status
    ) {
        $this->serviceName = $serviceName;
        $this->status = $status;
    }

    public function getServiceName(): IntegrationServiceEnum
    {
        return $this->serviceName;
    }

    public function setServiceName(IntegrationServiceEnum $serviceName): void
    {
        $this->serviceName = $serviceName;
    }

    public function getStatus(): IntegrationStatusEnum
    {
        return $this->status;
    }

    public function setStatus(IntegrationStatusEnum $status): void
    {
        $this->status = $status;
    }
}
