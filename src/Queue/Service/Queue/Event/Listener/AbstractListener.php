<?php

namespace Queue\Service\Queue\Event\Listener;

use Queue\Service\Queue\Event\EventInterface as QueueEventInterface;
use Queue\Service\Queue\Manager\ManagerInterface;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventInterface as ZendEventInterface;

/**
 * Менеджер очередей / Базовый zf2 event manager слушатель входящих событий
 *
 * Class AbstractListener
 * @package Queue\Service\Queue\Event\Listener
 */
abstract class AbstractListener extends AbstractListenerAggregate implements ListenerInterface
{
    /**
     * @var ManagerInterface
     */
    protected $queueManager;

    /**
     * Constructor
     *
     * @param ManagerInterface $queueManager
     */
    public function __construct(ManagerInterface $queueManager)
    {
        $this->queueManager = $queueManager;
    }

    /**
     * Вешаем слушатели
     *
     * @param EventManagerInterface $events
     */
    public function attach(EventManagerInterface $events)
    {
        $events->getSharedManager()->attach('*', $this->getEventType(), [$this, 'handle']);
    }

    /**
     * Получить объект события
     *
     * @param ZendEventInterface $event
     *
     * @return QueueEventInterface
     * @throws Exception
     */
    protected function getQueueEvent(ZendEventInterface $event)
    {
        if (!($event instanceof QueueEventInterface)) {
            $event = $event->getTarget();
        }
        if (!($event instanceof QueueEventInterface)) {
            throw new Exception('Передано неверное событие');
        }

        return $event;
    }
}
