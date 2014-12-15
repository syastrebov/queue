<?php

namespace Queue\Service\Queue\Event\Parser;

use Queue\Service\Queue\Event\EventInterface;
use Queue\Service\Queue\Event\Mock as Event;

/**
 * Менеджер очередей / Парсер тестового события
 *
 * Class Mock
 * @package Queue\Service\Queue\Event\Parser
 */
class Mock implements ParserPluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return EventInterface::TYPE_MOCK;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(EventInterface $event)
    {
        return [
            'type'         => $event->getType(),
            'exchangeName' => $event->getExchangeName(),
            'routingKey'   => $event->getRoutingKey(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function toEvent(array $event)
    {
        return new Event($event['exchangeName'], $event['routingKey']);
    }
}
