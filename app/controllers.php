<?php
use SonnyBlaine\Integrator\Controllers\IntegratorController;

$app['integrator.controller'] = function () use ($app) {
    return new IntegratorController($app['integrator.service']);
};