<?php

namespace SonnyBlaine\Integrator;

use Doctrine\ORM\Mapping as ORM;

trait DateTrait
{
    /**
     * @ORM\Column(type="datetime", name="created_in")
     * @var \DateTime
     */
    protected $createdIn;

    /**
     * @ORM\Column(type="datetime", name="success_in")
     * @var \DateTime
     */
    protected $successIn;

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
}