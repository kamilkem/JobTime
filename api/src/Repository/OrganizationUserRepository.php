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

use App\Entity\OrganizationUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OrganizationUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrganizationUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrganizationUser[]    findAll()
 * @method OrganizationUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrganizationUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrganizationUser::class);
    }
}
