<?php

namespace Queue\Service\Queue\Event\Parser;

use Queue\Service\Queue\Event\EventInterface;

/**
 * Менеджер очередей / Интерфейс парсера сообщения из очереди в объект события
 *
 * Interface ParserInterface
 * @package Queue\Service\Queue\Event\Parser
 */
interface ParserInterface
{
    /**
     * Преобразуем объект события в массив
     *
     * @param EventInterface $event
     * @return array
     */
    public function toArray(EventInterface $event);

    /**
     * Преобразуем массив в событие
     *
     * @param array $event
     * @return EventInterface
     */
    public function toEvent(array $event);
}
