<?php

namespace Queue\Service\Queue\Manager\Configuration;

use Queue\Service\Queue\Manager\Adapter\ConfigurationInterface as AdapterConfigurationInterface;
use Queue\Service\Queue\Manager\Queue;
use Queue\Service\Queue\Manager\Exchange;
use Queue\Service\Queue\Manager\Bind;

/**
 * Менеджер очередей / Конфигурация менеджера очередей
 *
 * Class Configuration
 * @package Queue\Service\Queue\Manager\Configuration
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @var AdapterConfigurationInterface
     */
    private $adapterConfig;

    /**
     * @var Queue\ConfigurationCollection
     */
    private $queueConfigurationCollection;

    /**
     * @var Exchange\ConfigurationCollection
     */
    private $exchangeConfigurationCollection;

    /**
     * @var Bind\Collection
     */
    private $bindCollection;

    /**
     * Задать конфигурацию адаптера
     *
     * @param AdapterConfigurationInterface $adapterConfig
     * @return $this
     */
    public function setAdapterConfig(AdapterConfigurationInterface $adapterConfig)
    {
        $this->adapterConfig = $adapterConfig;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAdapterConfig()
    {
        return $this->adapterConfig;
    }

    /**
     * Задать коллекцию конфигураций очередей
     *
     * @param Queue\ConfigurationCollection $collection
     * @return $this
     */
    public function setQueueCollection(Queue\ConfigurationCollection $collection)
    {
        $this->queueConfigurationCollection = $collection;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getQueueCollection()
    {
        return $this->queueConfigurationCollection;
    }

    /**
     * Задать коллекцию конфигураций очередей
     *
     * @param Exchange\ConfigurationCollection $collection
     * @return $this
     */
    public function setExchangeCollection(Exchange\ConfigurationCollection $collection)
    {
        $this->exchangeConfigurationCollection = $collection;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getExchangeCollection()
    {
        return $this->exchangeConfigurationCollection;
    }

    /**
     * Задать коллекцию конфигураций очередей
     *
     * @param Bind\Collection $collection
     * @return $this
     */
    public function setBindCollection(Bind\Collection $collection)
    {
        $this->bindCollection = $collection;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getBindCollection()
    {
        return $this->bindCollection;
    }
}
