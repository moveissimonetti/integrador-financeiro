<?php
use SonnyBlaine\Integrator\ConnectionManager;
use SonnyBlaine\Integrator\Services\RequestService;
use SonnyBlaine\Integrator\Destination;
use SonnyBlaine\Integrator\Source;
use SonnyBlaine\Integrator\Rabbit;
use SonnyBlaine\Integrator\Services\SourceService;
use SonnyBlaine\Integrator\BridgeFactory;
use SonnyBlaine\Integrator\Services\IntegratorService;

/* Repositories */
$app['source.request.repository'] = function () use ($app) {
    return $app['orm.em']->getRepository(Source\Request::class);
};

$app['destination.request.repository'] = function () use ($app) {
    return $app['orm.em']->getRepository(Destination\Request::class);
};

$app['source.repository'] = function () use ($app) {
    return $app['orm.em']->getRepository(Source\Source::class);
};

/* Services */
$app['connection_manager.service'] = function () use ($app) {
    return new ConnectionManager();
};

$app['destination.request.creator'] = function () use ($app) {
    return new Destination\RequestCreator($app['connection_manager.service']);
};

$app['request.service'] = function () use ($app) {
    return new RequestService(
        $app['destination.request.creator'],
        $app['source.request.repository'],
        $app['destination.request.repository']
    );
};

$app['source.service'] = function () use ($app) {
    return new SourceService($app['source.repository']);
};

$app['integrator.service'] = function () use ($app) {
    return new IntegratorService(
        $app['orm.em']->getConnection(),
        $app['source.service'],
        $app['request.service'],
        $app['rabbit.producer']['integrator_producer']
    );
};

$app['bridge.factory'] = function () use ($app) {
    return new BridgeFactory($app);
};

/* Rabbit Consumers */
$app['integrator_consumer'] = function () use ($app) {
    return new Rabbit\IntegratorConsumer(
        $app['request.service'],
        $app['rabbit.producer']['integrator_producer'],
        $app['bridge.factory']
    );
};