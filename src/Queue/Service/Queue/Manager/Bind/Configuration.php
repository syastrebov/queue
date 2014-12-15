<?php

namespace Queue\Service\Queue\Manager\Bind;

use Queue\Service\Queue\Manager\Exchange;
use Queue\Service\Queue\Manager\Queue;

/**
 * Менеджер очередей / Конфигурация связи обменника с очередью
 *
 * Class Configuration
 * @package Queue\Service\Queue\Manager\Bind
 */
class Configuration implements BindInterface
{
    /**
     * @var Exchange\Configuration
     */
    private $exchange;

    /**
     * @var Queue\Configuration
     */
    private $queue;

    /**
     * @var string
     */
    private $routingKey;

    /**
     * Constructor
     *
     * @param Exchange\Configuration $exchange
     * @param Queue\Configuration    $queue
     * @param string                 $routingKey
     */
    public function __construct(Exchange\Configuration $exchange, Queue\Configuration $queue, $routingKey)
    {
        $this->exchange   = $exchange;
        $this->queue      = $queue;
        $this->routingKey = (string)$routingKey;
    }

    /**
     * {@inheritdoc}
     */
    public function getExchangeConfiguration()
    {
        return $this->exchange;
    }

    /**
     * {@inheritdoc}
     */
    public function getQueueConfiguration()
    {
        return $this->queue;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoutingKey()
    {
        return $this->routingKey;
    }

    /**
     * {@inheritdoc}
     */
    public function getHash()
    {
        return md5($this->exchange->getName() . $this->queue->getName() . $this->routingKey);
    }
}
