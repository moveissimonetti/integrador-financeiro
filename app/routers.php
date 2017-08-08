<?php
$app->mount('/api', function ($api) {
    $api->post('/integrate/{sourceIdentifier}/{queryParameter}', 'integrate.controller:integrateAction');

    $api->get('/search/{sourceIdentifier}', 'integrate.controller:searchAction');

    $api->mount('/source', function ($source) {
        $source->get('/search', 'source.controller:searchAction');
        $source->get('/{sourceIdentifier}/requests', 'source.controller:fetchRequestsAction');
    });
});
