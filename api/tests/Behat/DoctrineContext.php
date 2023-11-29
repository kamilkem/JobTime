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

namespace App\Tests\Behat;

use App\DataFixtures\UserFixtures;
use Behat\Behat\Context\Context;
use Behat\Hook\BeforeScenario;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;

readonly class DoctrineContext implements Context
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[BeforeScenario]
    public function loadDataFixtures(): void
    {
        $loader = new Loader();
        $loader->addFixture(new UserFixtures());

        $purger = new ORMPurger();
        $executor = new ORMExecutor($this->entityManager, $purger);
        $executor->execute($loader->getFixtures());
    }
}
