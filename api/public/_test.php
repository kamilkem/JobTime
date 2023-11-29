<?php

use App\Kernel;
use Symfony\Component\ErrorHandler\Debug;

$_SERVER['APP_ENV']   = 'test';
$_SERVER['APP_DEBUG'] = true;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

Debug::enable();

return function () {
    return new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
};
