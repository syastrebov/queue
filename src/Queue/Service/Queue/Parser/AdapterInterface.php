<?php

namespace Queue\Service\Queue\Parser;

use Queue\Service\Queue\Manager\Adapter\ConfigurationInterface;

/**
 * Менеджер очередей / Интерфейс парсера конфигурации адаптера
 *
 * Class AdapterInterface
 * @package Queue\Service\Queue\Parser
 */
interface AdapterInterface
{
    /**
     * Тип парсера
     *
     * @return string
     */
    public function getType();

    /**
     * Получить конфигурацию адаптера
     *
     * @return ConfigurationInterface
     */
    public function getConfiguration();
}
