<?php

namespace SonnyBlaine\Integrator;

interface RequestStatusInterface
{
    const STATUS_SUCCESS = 'success';
    const STATUS_ERROR = 'error';
    const STATUS_PENDING = 'pending';

    public function getStatus();
}