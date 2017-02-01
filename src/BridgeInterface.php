<?php

namespace SonnyBlaine\Integrator;

use SonnyBlaine\Integrator\Destination\Request;

/**
 * Interface IntegratorInterface
 * @package SonnyBlaine\Integrator
 */
interface BridgeInterface
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function integrate(Request $request);
}