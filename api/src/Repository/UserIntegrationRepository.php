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

namespace App\Repository;

use App\Entity\UserIntegration;
use App\Model\UserIntegrationInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserIntegrationInterface|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserIntegrationInterface|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserIntegrationInterface[]    findAll()
 * @method UserIntegrationInterface[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserIntegrationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserIntegration::class);
    }
}
