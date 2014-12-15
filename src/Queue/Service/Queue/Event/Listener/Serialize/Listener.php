<?php

namespace Queue\Service\Queue\Event\Listener\Serialize;

use Zend\EventManager\EventInterface as ZendEventInterface;
use Queue\Service\Queue\Event\EventInterface as QueueEventInterface;
use Queue\Service\Queue\Event\Listener\AbstractListener;
use Queue\Service\Queue\Manager\Decoder\DecoderInterface;

/**
 * Менеджер очередей / Zf2 event manager слушатель входящих событий
 * Отправляет сообщения в очередь в сериализованном виде
 *
 * Class SerializeListener
 * @package Queue\Service\Queue\Event\Listener\Serialize
 */
final class Listener extends AbstractListener
{
    /**
     * {@inheritdoc}
     */
    public function getEventType()
    {
        return QueueEventInterface::TYPE_QUEUE_SERIALIZE_EVENT;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(ZendEventInterface $event)
    {
        $event = $this->getQueueEvent($event);
        $this->queueManager
            ->init()
            ->getExchange($event->getExchangeName())
            ->publish(
                $event->getRoutingKey(),
                $event,
                DecoderInterface::TYPE_SERIALIZE,
                $event->getAttemptCount(),
                $event->getPriority()
            );
    }
}
