<?php
use SonnyBlaine\Integrator\Controllers\SupervisorController;

$app->mount('/api', function ($api) {
    $api->post('/integrate/{sourceIdentifier}/{queryParameter}', 'integrator.controller:integrateAction');

    $api->get('/search/{sourceIdentifier}', 'integrator.controller:searchAction');

    $api->mount('/source', function ($source) {
        $source->get('/search', 'source.controller:searchAction');
        $source->get('/{sourceIdentifier}/requests', 'source.controller:fetchRequestsAction');
    });

    $api->mount('/supervisor', function ($supervisor) {
        $supervisor->get("/processes", "supervisor.controller:fetchProcessesAction");

        $supervisor->get("/{processGroup}/{processName}/{action}", "supervisor.controller:execProcessOperationAction")
            ->assert('processGroup', implode("|", SupervisorController::AVAILABLE_GROUPS))
            ->assert('action', implode("|", SupervisorController::AVAILABLE_ACTIONS));
    });
});
