<?php
namespace SonnyBlaine\Integrator;

/**
 * Interface ResponseInterface
 * @package SonnyBlaine\Integrator
 */
interface ResponseInterface
{
    public function setSuccess(bool $success);

    public function setMsg(?string $msg);

    public function setErrorTracer(?string $errorTracer);
}