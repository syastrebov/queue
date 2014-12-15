<?php

namespace Queue\Service\Queue\Parser;

use Queue\Service\Queue\Manager\Configuration\ConfigurationInterface;

/**
 * Менеджер очередей / Интерфейс парсера
 *
 * Interface ParserInterface
 * @package Queue\Service\Queue\Parser
 */
interface ParserInterface
{
    /**
     * Получить конфигурацию
     *
     * @return ConfigurationInterface
     */
    public function getConfiguration();
}
