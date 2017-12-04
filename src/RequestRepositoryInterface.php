<?php

namespace SonnyBlaine\Integrator;

interface RequestRepositoryInterface
{
    /**
     * @param AbstractRequest $request
     * @return void
     */
    public function save(AbstractRequest $request);

    /**
     * @param int $id
     * @return AbstractRequest
     */
    public function find($id);
}