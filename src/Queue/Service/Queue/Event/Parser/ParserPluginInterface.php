<?php

namespace Queue\Service\Queue\Event\Parser;

use Queue\Service\Queue\Consumer\PluginTypeInterface;

/**
 * Менеджер очередей / Интерфейс события плагина парсера сообщения из очереди в объект события
 *
 * Interface ParserPluginInterface
 * @package Queue\Service\Queue\Event\Parser
 */
interface ParserPluginInterface extends ParserInterface, PluginTypeInterface
{

}
