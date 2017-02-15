<?php
// MySQL
define('DB_DRIVER', getenv('DB_DRIVER') ?: 'pdo_mysql');
define('DB_HOST', getenv('DB_HOST') ?: 'db');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASSWORD', getenv('DB_PASSWORD') ?: 'root');
define('DB_NAME', getenv('DB_NAME') ?: 'test');

// Rabbit
define('RABBIT_HOST', getenv('RABBIT_HOST') ?: 'rabbit');
define('RABBIT_PORT', getenv('RABBIT_PORT') ?: 5672);
define('RABBIT_USER', getenv('RABBIT_USER') ?: 'guest');
define('RABBIT_PASSWORD', getenv('RABBIT_PASSWORD') ?: 'guest');
define('RABBIT_VHOST', getenv('RABBIT_VHOST') ?: '/');
define('APP_ENV', getenv('APP_ENV') ?: 'dev');