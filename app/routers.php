<?php
$app->mount('/api', function ($api) {
    $api->post('/integrate/{sourceIdentifier}/{queryParameter}', 'integrator.controller:integrateAction');

    $api->get('/search/{sourceIdentifier}', 'integrator.controller:searchAction');

    $api->mount('/source', function ($source) {
        $source->get('/search', 'source.controller:searchAction');
        $source->get('/{sourceIdentifier}/requests', 'source.controller:fetchRequestsAction');
    });
});
