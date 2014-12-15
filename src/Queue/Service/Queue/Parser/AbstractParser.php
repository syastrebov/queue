<?php

namespace Queue\Service\Queue\Parser;

use Queue\Service\Queue\Manager\Adapter\AdapterInterface as ManagerAdapterInterface;
use Queue\Service\Queue\Manager\Configuration\ConfigurationInterface;

/**
 * Менеджер очередей / Базовый парсер
 *
 * Class AbstractParser
 * @package Queue\Service\Queue\Parser
 */
abstract class AbstractParser implements ConfigurationInterface, ParserInterface
{
    /**
     * Список доступных адаптеров
     *
     * @return array
     */
    public function getAdapterTypes()
    {
        return [ManagerAdapterInterface::TYPE_RABBIT, ManagerAdapterInterface::TYPE_MOCK];
    }
}
