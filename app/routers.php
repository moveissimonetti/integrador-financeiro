<?php
$app->mount('/api', function ($api) {
    $api->post('/integrate/{sourceIdentifier}/{queryParameter}', 'integrator.controller:integrateAction');
});