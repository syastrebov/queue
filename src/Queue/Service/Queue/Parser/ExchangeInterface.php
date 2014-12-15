<?php

namespace Queue\Service\Queue\Parser;

use Queue\Service\Queue\Manager\Exchange\Configuration;

/**
 * Менеджер очередей / Интерфейс парсера конфигурации обменника
 *
 * Interface ExchangeInterface
 * @package Queue\Service\Queue\Parser
 */
interface ExchangeInterface
{
    /**
     * Получить конфигурацию адаптера
     *
     * @return Configuration
     */
    public function getConfiguration();
}
