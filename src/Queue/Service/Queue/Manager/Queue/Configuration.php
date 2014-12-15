<?php

namespace Queue\Service\Queue\Manager\Queue;

use Queue\Service\Queue\Manager\Bind;
use Queue\Service\Queue\Manager\Exchange;

/**
 * Менеджер очередей / Конфигурация очереди
 *
 * Class Configuration
 * @package Queue\Service\Queue\Manager\Queue
 */
final class Configuration
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $timeout;

    /**
     * @var int
     */
    private $maxPriority;

    /**
     * @var Bind\Configuration
     */
    private $timeoutRoute;

    /**
     * @var Bind\Configuration
     */
    private $rejectRoute;

    /**
     * Constructor
     *
     * @param string $name
     * @param int    $timeout
     */
    public function __construct($name, $timeout = 0)
    {
        $this->name     = $name;
        $this->timeout  = $timeout;
    }

    /**
     * Название очереди
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Время жизни сообщения
     *
     * @return int
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * Время жизни сообщения
     *
     * @return int
     */
    public function getMicroTimeout()
    {
        return $this->timeout * 1000;
    }

    /**
     * Очередь с приоритетом
     *
     * @param int $maxPriority
     * @return $this
     */
    public function setMaxPriority($maxPriority)
    {
        $this->maxPriority = (int)$maxPriority;
        return $this;
    }

    /**
     * Очередь с приоритетом
     *
     * @return int
     */
    public function getMaxPriority()
    {
        return $this->maxPriority;
    }

    /**
     * Задать роут при протухании
     *
     * @param Exchange\Configuration $exchangeConfiguration
     * @param string                 $routingKey
     *
     * @return $this
     */
    public function setTimeoutRoute(Exchange\Configuration $exchangeConfiguration, $routingKey)
    {
        $this->timeoutRoute = new Bind\Configuration($exchangeConfiguration, $this, $routingKey);
        return $this;
    }

    /**
     * Роут при протухании сообщения
     *
     * @return Bind\Configuration|null
     */
    public function getTimeoutRoute()
    {
        return $this->timeoutRoute ? clone $this->timeoutRoute : null;
    }

    /**
     * Задать роут при ошибке в обработке
     *
     * @param Exchange\Configuration $exchangeConfiguration
     * @param string                 $routingKey
     *
     * @return $this
     */
    public function setRejectRoute(Exchange\Configuration $exchangeConfiguration, $routingKey)
    {
        $this->rejectRoute = new Bind\Configuration($exchangeConfiguration, $this, $routingKey);
        return $this;
    }

    /**
     * Роут при ошибке в обработке
     *
     * @return Bind\Configuration|null
     */
    public function getRejectRoute()
    {
        return $this->rejectRoute ? clone $this->rejectRoute : null;
    }
}
