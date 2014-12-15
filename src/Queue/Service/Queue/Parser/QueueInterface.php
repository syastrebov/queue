<?php

namespace Queue\Service\Queue\Parser;

use Queue\Service\Queue\Manager\Queue\Configuration;

/**
 * Менеджер очередей / Интерфейс парсера конфигурации очереди
 *
 * Interface QueueInterface
 * @package Queue\Service\Queue\Parser
 */
interface QueueInterface extends SetExchangeCollectionInterface
{
    /**
     * Получить конфигурацию адаптера
     *
     * @return Configuration
     */
    public function getConfiguration();
}
