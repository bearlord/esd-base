<?php
/**
 * ESD framework
 * @author bearlord <565364226@qq.com>
 */

namespace ESD\Plugins\Amqp\Message;

/**
 * Class ConsumerMessage
 * @package ESD\Plugins\Amqp\Message
 */
class ConsumerMessage extends Message
{
    /**
     * @var string
     */
    protected $queue;

    /**
     * @var bool
     */
    protected $requeue = true;

    /**
     * @var array
     */
    protected $routingKey = [];

    /**
     * @var null|array
     */
    protected $qos = [
        'prefetch_size' => 0,
        'prefetch_count' => 1,
        'global' => false,
    ];

    /**
     * @var bool
     */
    protected $enable = true;

    /**
     * @var int
     */
    protected $maxConsumption = 0;

    /**
     * @var float|int
     */
    protected $waitTimeout = 0;

    /**
     * @return string
     */
    public function getQueue(): string
    {
        return $this->queue;
    }

    /**
     * @param string $queue
     */
    public function setQueue(string $queue): void
    {
        $this->queue = $queue;
    }

    /**
     * @return bool
     */
    public function isRequeue(): bool
    {
        return $this->requeue;
    }

    /**
     * @param bool $requeue
     */
    public function setRequeue(bool $requeue): void
    {
        $this->requeue = $requeue;
    }

    /**
     * @return array|null
     */
    public function getQos(): ?array
    {
        return $this->qos;
    }

    /**
     * @param array|null $qos
     */
    public function setQos(?array $qos): void
    {
        $this->qos = $qos;
    }

    /**
     * @return bool
     */
    public function isEnable(): bool
    {
        return $this->enable;
    }

    /**
     * @param bool $enable
     */
    public function setEnable(bool $enable): void
    {
        $this->enable = $enable;
    }

    /**
     * @return int
     */
    public function getMaxConsumption(): int
    {
        return $this->maxConsumption;
    }

    /**
     * @param int $maxConsumption
     */
    public function setMaxConsumption(int $maxConsumption): void
    {
        $this->maxConsumption = $maxConsumption;
    }

    /**
     * @return float|int
     */
    public function getWaitTimeout()
    {
        return $this->waitTimeout;
    }

    /**
     * @param float|int $waitTimeout
     */
    public function setWaitTimeout($waitTimeout): void
    {
        $this->waitTimeout = $waitTimeout;
    }
    
}