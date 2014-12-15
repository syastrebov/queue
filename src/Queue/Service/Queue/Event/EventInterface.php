<?php

namespace Queue\Service\Queue\Event;

use Queue\Service\Queue\Consumer\PluginTypeInterface;

/**
 * Менеджер очередей / Интерфейс события обрабатываемого через очередь
 *
 * Interface EventInterface
 * @package Queue\Service\Queue\Event
 */
interface EventInterface extends PluginTypeInterface
{
    const TYPE_QUEUE_MOCK_EVENT      = 'QueueMockEvent';
    const TYPE_QUEUE_PARSER_EVENT    = 'QueueParserEvent';
    const TYPE_QUEUE_SERIALIZE_EVENT = 'QueueSerializeEvent';

    const TYPE_MOCK      = 1;
    const TYPE_LANDING   = 2;
    const TYPE_ACQUIRING = 3;
    const TYPE_SEGMENT   = 4;

    /**
     * Имя обменника через который отправлять сообщение
     *
     * @return string
     */
    public function getExchangeName();

    /**
     * Роут для очередей
     *
     * @return string
     */
    public function getRoutingKey();

    /**
     * Количество попыток отправки
     *
     * @return int
     */
    public function getAttemptCount();

    /**
     * Задать приоритет обработки
     *
     * @return int
     */
    public function getPriority();
}
