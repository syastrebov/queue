<?php

namespace Queue\Service\Queue\Manager;

/**
 * Менеджер очередей / Интерфейс менеджера очередей
 *
 * Interface ManagerInterface
 * @package Queue\Service\Queue\Manager
 */
interface ManagerInterface
{
    const EVENT_QUEUE        = 'eventQueue';
    const PHANTOM_JS_QUEUE   = 'phantomJsQueue';
    const NOTIFICATION_QUEUE = 'notificationQueue';
    const SOCIAL_USER_QUEUE  = 'socialUserQueue';

    /**
     * Связать очереди
     *
     * @return $this
     */
    public function init();

    /**
     * Получить ссылку на обменник
     *
     * @param string $name
     * @return Exchange\ExchangeInterface
     */
    public function getExchange($name);

    /**
     * Получить ссылку на очередь
     *
     * @param string $name
     * @return Queue\QueueInterface
     */
    public function getQueue($name);
}
