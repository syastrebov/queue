<?php

namespace Queue\Service\Queue\Manager\Exchange;

/**
 * Менеджер очередей / Интерфейс обменника
 *
 * Interface ExchangeInterface
 * @package Queue\Service\Queue\Manager\Exchange
 */
interface ExchangeInterface
{
    const TYPE_DIRECT = 'direct';
    const TYPE_TOPIC  = 'topic';
    const TYPE_FANOUT = 'fanout';

    const NAME_LANDING   = 'landing';
    const NAME_ACQUIRING = 'acquiring';
    const NAME_SEGMENT   = 'segment';

    /**
     * Название обменника
     *
     * @return string
     */
    public function getName();

    /**
     * Отправить сообщение в очередь
     *
     * @param string $routingKey
     * @param mixed  $message
     * @param string $contentType
     * @param int    $attempt
     * @param int    $priority
     *
     * @return $this
     */
    public function publish($routingKey, $message, $contentType, $attempt = 0, $priority = 0);
}
