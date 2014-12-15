<?php

namespace Queue\Service\Queue\Event\Listener;

use Queue\Service\Queue\Event\EventInterface as QueueEventInterface;
use Zend\EventManager\EventInterface as ZendEventInterface;

/**
 * Менеджер очередей / Zf2 event manager слушатель входящих событий для тестов
 *
 * Class Mock
 * @package Queue\Service\Queue\Event\Listener
 */
class Mock extends AbstractListener
{
    /**
     * @var array
     */
    private $events = [];

    /**
     * Constructor
     */
    public function __construct()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function getEventType()
    {
        return QueueEventInterface::TYPE_QUEUE_PARSER_EVENT;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(ZendEventInterface $event)
    {
        $this->events[] = $this->getQueueEvent($event);
    }

    /**
     * События в слушателе
     *
     * @return array
     */
    public function getEvents()
    {
        return $this->events;
    }
}
