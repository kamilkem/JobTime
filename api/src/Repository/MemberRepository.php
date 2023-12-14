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

use App\Entity\Member;
use App\Model\MemberInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MemberInterface|null find($id, $lockMode = null, $lockVersion = null)
 * @method MemberInterface|null findOneBy(array $criteria, array $orderBy = null)
 * @method MemberInterface[]    findAll()
 * @method MemberInterface[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MemberRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Member::class);
    }
}
