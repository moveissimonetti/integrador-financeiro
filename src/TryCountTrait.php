<?php
namespace SonnyBlaine\Integrator;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait TryCount
 * @package SonnyBlaine\Integrator
 */
trait TryCountTrait
{
    /**
     * @ORM\Column(name="try_count", type="smallint")
     * @var int
     */
    protected $tryCount = 0;

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
}