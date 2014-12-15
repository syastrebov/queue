<?php

namespace Queue\Service\Queue\Manager\Exchange;

use Queue\Service\Queue\Manager\Adapter\AdapterInterface;

/**
 * Менеджер очередей / Обменник
 *
 * Class Exchange
 * @package Queue\Service\Queue\Manager\Exchange
 */
final class Exchange implements ExchangeInterface
{
    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * Constructor
     *
     * @param AdapterInterface $adapter
     * @param Configuration    $configuration
     */
    public function __construct(AdapterInterface $adapter, Configuration $configuration)
    {
        $this->adapter       = $adapter;
        $this->configuration = $configuration;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->configuration->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function publish($routingKey, $message, $contentType, $attempt = 0, $priority = 0)
    {
        $this->adapter->publish($this->configuration, $routingKey, $message, $contentType, $attempt, $priority);
        return $this;
    }
}
