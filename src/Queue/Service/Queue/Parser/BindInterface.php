<?php

namespace Queue\Service\Queue\Parser;

use Queue\Service\Queue\Manager\Bind\Configuration;

/**
 * Менеджер очередей / Интерфейс парсера конфигурации связи
 *
 * Interface BindInterface
 * @package Queue\Service\Queue\Parser
 */
interface BindInterface extends SetQueueCollectionInterface, SetExchangeCollectionInterface
{
    /**
     * Получить конфигурацию адаптера
     *
     * @return Configuration
     */
    public function getConfiguration();
}
