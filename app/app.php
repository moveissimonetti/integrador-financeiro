<?php
use Silex\Application;

if (!defined('APP_PATH')) {
    define('APP_PATH', __DIR__);
}

require_once __DIR__ . '/config.php';

$app = new Application();

if ('prod' == APP_ENV) {
    $app['debug'] = true;
}

require_once __DIR__ . '/providers.php';
include_once __DIR__ . '/controllers.php';
require_once __DIR__ . '/services.php';
require_once __DIR__ . '/routers.php';

if (file_exists(__DIR__ . '/providers_bridges.php')) {
    require_once __DIR__ . '/providers_bridges.php';
}

return $app;
