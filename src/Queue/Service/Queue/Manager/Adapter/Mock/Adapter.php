<?php

namespace Queue\Service\Queue\Manager\Adapter\Mock;

use Queue\Entity\Message;
use Queue\Service\Queue\Manager\Adapter\AdapterInterface;
use Queue\Service\Queue\Manager\Message\EncodedMessage;
use Queue\Service\Queue\Manager\Message\EncodedMessageCollection;
use Queue\Service\Queue\Manager\Exchange;
use Queue\Service\Queue\Manager\Queue;

/**
 * Менеджер очередей / Заглушка для адаптера
 *
 * Class Adapter
 * @package Queue\Service\Queue\Manager\Adapter\Mock
 */
final class Adapter implements AdapterInterface
{
    /**
     * @var EncodedMessageCollection
     */
    private $messageCollection;

    /**
     * Constructor
     *
     * @param Configuration $config
     */
    public function __construct(Configuration $config)
    {
        $this->messageCollection = $config->getEncodedMessageCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function init()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function bind(Exchange\Configuration $exchangeConfig, Queue\Configuration $queueConfig, $routingKey)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function publish(
        Exchange\Configuration $configuration,
        $routingKey,
        $message,
        $contentType,
        $attempt,
        $priority
    ) {
        $this->messageCollection->attach(new EncodedMessage(
            new Message($this->messageCollection->getNextId(), $message, 0),
            $contentType)
        );

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function get(Queue\Configuration $configuration)
    {
        return $this->messageCollection->getFirst();
    }

    /**
     * {@inheritdoc}
     */
    public function ack(Queue\Configuration $configuration, $id)
    {
        $this->messageCollection->detachById($id);
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function nack(Queue\Configuration $configuration, $id)
    {
        $message = $this->messageCollection->getById($id);
        $this->messageCollection->detachById($id);
        $this->messageCollection->attach($message);

        return true;
    }
}
