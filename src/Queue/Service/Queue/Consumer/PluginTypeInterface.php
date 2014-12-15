<?php

namespace Queue\Service\Queue\Consumer;

/**
 * Менеджер очередей / Интерфейс плагина с типом
 *
 * Interface PluginTypeInterface
 * @package Queue\Service\Queue\Consumer
 */
interface PluginTypeInterface
{
    /**
     * Тип события
     *
     * @return int
     */
    public function getType();
}
