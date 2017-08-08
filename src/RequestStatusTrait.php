<?php

namespace SonnyBlaine\Integrator;

use SonnyBlaine\Integrator\Rabbit\RequestCreatorConsumer;

trait RequestStatusTrait
{
    public abstract function getTryCount(): int;

    public abstract function isSuccess(): bool;

    /**
     * @return string
     */
    public function getStatus()
    {
        if ($this->isSuccess()) {
            return RequestStatusInterface::STATUS_SUCCESS;
        }

        if ($this->getTryCount() == RequestCreatorConsumer::MAX_TRY_COUNT) {
            return RequestStatusInterface::STATUS_ERROR;
        }

        return RequestStatusInterface::STATUS_PENDING;
    }
}