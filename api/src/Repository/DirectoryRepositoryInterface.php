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

use App\Model\DirectoryInterface;

/**
 * @method DirectoryInterface|null find($id, $lockMode = null, $lockVersion = null)
 * @method DirectoryInterface|null findOneBy(array $criteria, array $orderBy = null)
 * @method DirectoryInterface[]    findAll()
 * @method DirectoryInterface[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
interface DirectoryRepositoryInterface extends RepositoryInterface
{
}
