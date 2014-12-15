<?php

namespace Queue\Service\Queue\Event\Listener;

use Zend\EventManager\EventInterface as ZendEventInterface;

/**
 * Менеджер очередей / Интерфейс zf2 event manager слушателя входящих событий
 *
 * Interface ListenerInterface
 * @package Queue\Service\Queue\Event\Listener
 */
interface ListenerInterface
{
    /**
     * Слушаемое событие
     *
     * @return string
     */
    public function getEventType();

    /**
     * Обработка события
     *
     * @param ZendEventInterface $event
     * @return mixed
     * @throws Exception
     */
    public function handle(ZendEventInterface $event);
}
