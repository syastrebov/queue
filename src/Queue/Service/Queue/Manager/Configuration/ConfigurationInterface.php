<?php

namespace Queue\Service\Queue\Manager\Configuration;

use Queue\Service\Queue\Manager\Queue;
use Queue\Service\Queue\Manager\Exchange;
use Queue\Service\Queue\Manager\Bind;

/**
 * Менеджер очередей / Интерфейс парсера конфигурации
 *
 * Class ConfigurationInterface
 * @package Queue\Service\Queue\Manager\Configuration
 */
interface ConfigurationInterface
{
    /**
     * Конфигурация адаптера
     *
     * @return \Queue\Service\Queue\Manager\Adapter\ConfigurationInterface
     */
    public function getAdapterConfig();

    /**
     * Коллекция очередей
     *
     * @return Queue\ConfigurationCollection
     */
    public function getQueueCollection();

    /**
     * Коллекция обменников
     *
     * @return Exchange\ConfigurationCollection
     */
    public function getExchangeCollection();

    /**
     * Коллекция связей
     *
     * @return Bind\Collection
     */
    public function getBindCollection();
}
