<?php

namespace Queue\Service\Queue\Event;

/**
 * Менеджер очередей / Тестовое событие
 *
 * Class Mock
 * @package Queue\Service\Queue\Event
 */
class Mock extends AbstractEvent
{
    /**
     * @var string
     */
    private $exchangeName;

    /**
     * @var string
     */
    private $routingKey;

    /**
     * @var int
     */
    private $attemptCount;

    /**
     * @var int
     */
    private $priority;

    /**
     * Constructor
     *
     * @param string $exchangeName
     * @param string $routingKey
     * @param int    $attemptCount
     * @param int    $priority
     */
    public function __construct($exchangeName, $routingKey, $attemptCount, $priority)
    {
        $this->exchangeName = $exchangeName;
        $this->routingKey   = $routingKey;
        $this->attemptCount = (int)$attemptCount;
        $this->priority     = (int)$priority;
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return EventInterface::TYPE_MOCK;
    }

    /**
     * {@inheritdoc}
     */
    public function getExchangeName()
    {
        return $this->exchangeName;
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
    public function getAttemptCount()
    {
        return $this->attemptCount;
    }

    /**
     * {@inheritdoc}
     */
    public function getPriority()
    {
        return $this->priority;
    }
}
