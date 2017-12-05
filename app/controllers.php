<?php

use SonnyBlaine\Integrator\Controllers\IntegratorController;
use SonnyBlaine\Integrator\Controllers\GetController;
use SonnyBlaine\Integrator\Controllers;

$app['integrator.controller'] = function () use ($app) {
    return new IntegratorController(
        $app['integrator.service'],
        $app['search.service'],
        $app['source.request.repository']
    );
};

$app['source.controller'] = function () use ($app) {
    return new Controllers\SourceController($app['source.service'], $app['request.service']);
};

$app['supervisor.controller'] = function () use ($app) {
    return new Controllers\SupervisorController($app['supervisor']);
};