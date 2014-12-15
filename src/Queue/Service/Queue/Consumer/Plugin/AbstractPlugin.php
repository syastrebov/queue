<?php

namespace Queue\Service\Queue\Consumer\Plugin;

use Queue\Service\Queue\Event\EventInterface;

/**
 * Менеджер очередей / Базовы плагин обработки события
 *
 * Class AbstractPlugin
 * @package Queue\Service\Queue\Consumer\Plugin
 */
abstract class AbstractPlugin implements PluginInterface
{
    /**
     * @var EventInterface
     */
    protected $event;

    /**
     * {@inheritdoc}
     */
    public function setEvent(EventInterface $event)
    {
        $this->event = $event;
        return $this;
    }
}
