<?php
use SonnyBlaine\Integrator\Controllers\IntegratorController;

$app['integrator.controller'] = function () use ($app) {
    return new IntegratorController(
        $app['source.service'],
        $app['request.service'],
        $app['rabbit.producer']['integrator_producer']
    );
};