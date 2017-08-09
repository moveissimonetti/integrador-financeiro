<?php

namespace SonnyBlaine\Integrator\Controllers;

use Supervisor\Supervisor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SupervisorController
{
    const AVAILABLE_GROUPS = ['integrator', 'request_creator'];
    const AVAILABLE_PROCESS_KEYS = ['description', 'group', 'name', 'statename', 'start', 'now', 'state'];
    const AVAILABLE_ACTIONS = ['start', 'stop', 'restart'];

    const ACTION_START = 'start';
    const ACTION_STOP = 'stop';
    const ACTION_RESTART = 'restart';

    /**
     * @var Supervisor
     */
    private $supervisor;

    /**
     * SupervisorController constructor.
     * @param Supervisor $supervisor
     */
    function __construct(Supervisor $supervisor)
    {
        $this->supervisor = $supervisor;
    }

    /**
     * @return JsonResponse
     */
    public function fetchProcessesAction()
    {
        $filterProcessesByGroup = function (array $process) {
            return in_array($process['group']??'', self::AVAILABLE_GROUPS);
        };

        $filterProcessKeys = function ($key) {
            return in_array($key, self::AVAILABLE_PROCESS_KEYS);
        };

        $mapProcesses = function (array $process) use ($filterProcessKeys) {
            return array_filter($process, $filterProcessKeys, ARRAY_FILTER_USE_KEY);
        };

        $processes = array_map(
            $mapProcesses, array_filter(
                $this->supervisor->getAllProcessInfo(), $filterProcessesByGroup
            )
        );

        return new JsonResponse($processes);
    }

    /**
     * @param Request $request
     * @param $processGroup
     * @param $processName
     * @param $action
     * @return $this
     */
    public function execProcessOperationAction(Request $request, $processGroup, $processName, $action)
    {
        $fullName = "$processGroup:$processName";

        $responseData = [
            'processGroup' => $processGroup,
            'processName' => $processName,
            'fullName' => $fullName,
            'action' => $action
        ];

        try {
            switch ($action) {
                case self::ACTION_START:
                    $this->supervisor->startProcess($fullName);
                    break;
                case self::ACTION_STOP:
                    $this->supervisor->stopProcess($fullName);
                    break;
                case self::ACTION_RESTART:
                    $this->supervisor->stopProcess($fullName);
                    $this->supervisor->startProcess($fullName);
            }

            $responseData['status'] = 'success';
            $code = 200;
        } catch (\Exception $e) {
            $responseData = array_merge($responseData, [
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
            $code = 500;
        }

        return (new JsonResponse($responseData, $code))
            ->setEncodingOptions(JSON_PRETTY_PRINT);
    }
}