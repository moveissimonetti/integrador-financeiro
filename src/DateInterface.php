<?php

namespace SonnyBlaine\Integrator;

interface DateInterface
{
    public function getCreatedIn();

    public function getSuccessIn();

    public function setSuccessIn(\DateTime $dateTime);
}