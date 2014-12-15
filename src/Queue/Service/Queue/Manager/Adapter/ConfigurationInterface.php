<?php

namespace Queue\Service\Queue\Manager\Adapter;

/**
 * Менеджер очередей / Интерфейс конфигурации
 *
 * Interface ConfigurationInterface
 * @package Queue\Service\Queue\Manager\Adapter
 */
interface ConfigurationInterface
{
    /**
     * Тип конфигурации
     *
     * @return string
     */
    public function getType();
}
