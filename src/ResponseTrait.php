<?php
namespace SonnyBlaine\Integrator;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class ResponseTrait
 * @package SonnyBlaine\Integrator
 */
trait ResponseTrait
{
    /**
     * @ORM\Column(name="success", type="boolean", nullable=true)
     * @var bool
     */
    protected $success;

    /**
     * @ORM\Column(name="msg", type="text", nullable=true)
     * @var ?string
     */
    protected $msg;

    /**
     * @ORM\Column(name="error_tracer", type="text", nullable=true)
     * @var string
     */
    protected $errorTracer;

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }

    /**
     * @param bool $success
     */
    public function setSuccess(bool $success)
    {
        $this->success = $success;
    }

    /**
     * @return string
     */
    public function getMsg(): ?string
    {
        return $this->msg;
    }

    /**
     * @param string|null $msg
     */
    public function setMsg(?string $msg)
    {
        $this->msg = $msg;
    }

    /**
     * @return string
     */
    public function getErrorTracer(): string
    {
        return $this->errorTracer;
    }

    /**
     * @param string|null $errorTracer
     */
    public function setErrorTracer(?string $errorTracer)
    {
        $this->errorTracer = $errorTracer;
    }
}