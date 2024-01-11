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

use App\Kernel;
use Symfony\Component\ErrorHandler\Debug;

$_SERVER['APP_ENV']   = 'test';
$_SERVER['APP_DEBUG'] = true;

require_once dirname(__DIR__) . '/vendor/autoload_runtime.php';

Debug::enable();

return function () {
    return new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
};
