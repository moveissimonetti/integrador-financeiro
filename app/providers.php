<?php
$app->register(new Silex\Provider\ServiceControllerServiceProvider());

$app->register(
    new Silex\Provider\MonologServiceProvider(),
    [
        'monolog.logfile' => __DIR__ . '/logs/development.log',
    ]
);

$app['monolog'] = $app->extend('monolog', function ($monolog, $app) {
    $monolog->pushHandler(
        new \Monolog\Handler\RotatingFileHandler($app['monolog.logfile'], 90, \Monolog\Logger::DEBUG)
    );

    return $monolog;
});

$app->register(new \Silex\Provider\DoctrineServiceProvider(), [
    'db.options' => [
        'driver'   => DB_DRIVER,
        'dbname'   => DB_NAME,
        'host'     => DB_HOST,
        'user'     => DB_USER,
        'password' => DB_PASSWORD,
    ],
]);

$app->register(new \Dflydev\Provider\DoctrineOrm\DoctrineOrmServiceProvider(), [
    'orm.proxies_dir' => __DIR__ . '/cache/doctrine/proxies',
    'orm.em.options'  => [
        'mappings' => [
            [
                'type'                         => 'annotation',
                'namespace'                    => 'SonnyBlaine\Integrator',
                'path'                         => __DIR__ . '/../src',
                'use_simple_annotation_reader' => false,
            ],
        ],
    ],
]);
$loader = require __DIR__ . '/../vendor/autoload.php';
\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

if ($app['debug']) {
    $logger = new Doctrine\DBAL\Logging\DebugStack();
    $app['db.config']->setSQLLogger($logger);
    $app->error(function (\Exception $e, $code) use ($app, $logger) {
        if ($e instanceof PDOException and count($logger->queries)) {
            // We want to log the query as an ERROR for PDO exceptions!
            $query = array_pop($logger->queries);
            $app['monolog']->err($query['sql'], array(
                'params' => $query['params'],
                'types'  => $query['types'],
            ));
        }
    });
    $app->after(function (
        \Symfony\Component\HttpFoundation\Request $request,
        \Symfony\Component\HttpFoundation\Response $response
    ) use ($app, $logger) {
        // Log all queries as DEBUG.
        foreach ($logger->queries as $query) {
            $app['monolog']->debug($query['sql'], array(
                'params' => $query['params'],
                'types'  => $query['types'],
            ));
        }
    });
}

$app->register(new \fiunchinho\Silex\Provider\RabbitServiceProvider(),
    [
        'rabbit.connections' => [
            'default' => [
                'host'     => RABBIT_HOST,
                'port'     => RABBIT_PORT,
                'user'     => RABBIT_USER,
                'password' => RABBIT_PASSWORD,
                'vhost'    => RABBIT_VHOST,
            ],
        ],

        // Producers
        'rabbit.producers' => [
            'integrator_producer'  => [
                'connection'       => 'default',
                'exchange_options' => ['name' => 'integrator_e', 'type' => 'topic'],
                'queue_options'    => ['name' => 'integrator'],
            ],
            'request_creator_producer'  => [
                'connection'       => 'default',
                'exchange_options' => ['name' => 'request_creator_e', 'type' => 'topic'],
                'queue_options'    => ['name' => 'request_creator'],
            ],
        ],

        // Consumers
        'rabbit.consumers' => [
            'integrator_consumer'  => [
                'connection'       => 'default',
                'exchange_options' => ['name' => 'integrator_e', 'type' => 'topic'],
                'queue_options'    => ['name' => 'integrator'],
                'callback'         => 'integrator_consumer',
            ],
            'request_creator_consumer'  => [
                'connection'       => 'default',
                'exchange_options' => ['name' => 'request_creator_e', 'type' => 'topic'],
                'queue_options'    => ['name' => 'request_creator'],
                'callback'         => 'request_creator_consumer',
            ],
        ],
    ]
);