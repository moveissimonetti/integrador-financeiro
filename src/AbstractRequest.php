<?php

namespace SonnyBlaine\Integrator;

use Doctrine\ORM\Mapping as ORM;
use SonnyBlaine\Integrator\Rabbit\RequestCreatorConsumer;

abstract class AbstractRequest
{
    const STATUS_SUCCESS = 'success';
    const STATUS_ERROR = 'error';
    const STATUS_PENDING = 'pending';

    /**
     * Request ID
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", name="id")
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(type="datetime", name="created_in")
     * @var \DateTime
     */
    protected $createdIn;

    /**
     * @ORM\Column(name="try_count", type="smallint")
     * @var int
     */
    protected $tryCount;

    /**
     * @ORM\Column(name="success", type="boolean", options={"default" = false})
     * @var bool
     */
    protected $success;

    /**
     * @ORM\Column(type="datetime", name="success_in", nullable=true)
     * @var \DateTime|null
     */
    protected $successIn;

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
     * @ORM\Column(name="cancelled", type="boolean", options={"default" : 0})
     *
     * @var bool
     */
    protected $cancelled;

    /**
     * @ORM\Column(name="cancelled_in", type="datetime", nullable=true)
     *
     * @var \DateTime|null
     */
    protected $cancelledIn;

    /**
     * AbstractRequest constructor.
     */
    public function __construct()
    {
        $this->createdIn = new \DateTime();
        $this->successIn = null;
        $this->tryCount = 0;
        $this->success = false;
        $this->msg = null;
        $this->errorTracer = null;
        $this->cancelled = false;
        $this->cancelledIn = null;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedIn(): \DateTime
    {
        return $this->createdIn;
    }

    /**
     * @return \DateTime|null
     */
    public function getSuccessIn():?\DateTime
    {
        return $this->successIn;
    }

    /**
     * @param mixed $successIn
     */
    public function setSuccessIn(\DateTime $successIn)
    {
        $this->successIn = $successIn;
    }

    /**
     * @return int
     */
    public function getTryCount(): int
    {
        return $this->tryCount;
    }

    /**
     * @param int $tryCount
     */
    public function setTryCount(int $tryCount)
    {
        $this->tryCount = $tryCount;
    }

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

    /**
     * @return string
     */
    public function getStatus()
    {
        if ($this->isSuccess()) {
            return self::STATUS_SUCCESS;
        }

        if ($this->getTryCount() == RequestCreatorConsumer::MAX_TRY_COUNT) {
            return self::STATUS_ERROR;
        }

        return self::STATUS_PENDING;
    }

    /**
     * @return bool
     */
    public function isCancelled(): bool
    {
        return $this->cancelled;
    }

    /**
     * @param bool $cancelled
     */
    public function setCancelled(bool $cancelled)
    {
        if ($this->cancelled) {
            throw new \Exception("Essa requisição já está cancelada!");
        }

        $this->cancelled = $cancelled;
        $this->cancelledIn = new \DateTime();
    }
}