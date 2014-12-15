<?php

namespace Queue\Service\Queue\Consumer\Plugin;

use Queue\Service\Queue\Consumer\PluginTypeInterface;
use Queue\Service\Queue\Consumer\Response\Response;
use Queue\Service\Queue\Event\EventInterface;

/**
 * Менеджер очередей / Интерфейс обработчика события полученного из очереди
 *
 * Interface PluginInterface
 * @package Queue\Service\Queue\Consumer
 */
interface PluginInterface extends PluginTypeInterface
{
    const TYPE_MOCK      = 1;
    const TYPE_LANDING   = 2;
    const TYPE_ACQUIRING = 3;
    const TYPE_SEGMENT   = 4;

    /**
     * Задать событие
     *
     * @param EventInterface $event
     * @return mixed
     */
    public function setEvent(EventInterface $event);

    /**
     * Плагин может обработать событие
     *
     * @return bool
     */
    public function shouldStart();

    /**
     * Обработка события
     *
     * @return Response|null
     * @throws PluginException
     */
    public function apply();
}
