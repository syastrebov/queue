<?php

namespace Queue\Service\Queue\Manager\Decoder;

use Queue\Entity\Message;
use Queue\Entity\MessageInterface;
use Queue\Service\Queue\Manager\Adapter\AdapterInterface;
use Queue\Service\Queue\Manager\Message\EncodedMessage;
use Queue\Service\Queue\Manager\Exchange;
use Queue\Service\Queue\Manager\Queue;
use Exception;

/**
 * Менеджер очередей / Декодер для адаптера
 *
 * Class Adapter
 * @package Queue\Service\Queue\Manager\Decoder
 */
final class Adapter implements AdapterInterface
{
    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * @var Collection
     */
    private $decoderCollection;

    /**
     * Constructor
     *
     * @param AdapterInterface $adapter
     * @param Collection        $decoderCollection
     */
    public function __construct(AdapterInterface $adapter, Collection $decoderCollection)
    {
        $this->adapter           = $adapter;
        $this->decoderCollection = $decoderCollection;
    }

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        return $this->adapter->init();
    }

    /**
     * {@inheritdoc}
     */
    public function bind(Exchange\Configuration $exchangeConfig, Queue\Configuration $queueConfig, $routingKey)
    {
        return $this->adapter->bind($exchangeConfig, $queueConfig, $routingKey);
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
        return $this->adapter->publish(
            $configuration,
            $routingKey,
            $this->getDecoderByContentType($contentType)->encode($message),
            $contentType,
            $attempt,
            $priority
        );
    }

    /**
     * {@inheritdoc}
     */
    public function get(Queue\Configuration $configuration)
    {
        $message = $this->adapter->get($configuration);
        if ($message instanceof MessageInterface) {
            if (!($message instanceof EncodedMessage)) {
                throw new Exception('Получен неверный тип сообщения');
            }

            return new Message(
                $message->getId(),
                $this->getDecoderByContentType($message->getContentType())->decode($message->getMessage()),
                $message->getAttempt()
            );
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function ack(Queue\Configuration $configuration, $id)
    {
        return $this->adapter->ack($configuration, $id);
    }

    /**
     * {@inheritdoc}
     */
    public function nack(Queue\Configuration $configuration, $id)
    {
        return $this->adapter->nack($configuration, $id);
    }

    /**
     * Получить декодер для сообщения
     *
     * @param string $contentType
     * @return \Queue\Service\Queue\Manager\Decoder\DecoderInterface
     */
    private function getDecoderByContentType($contentType)
    {
        return $this->decoderCollection->getByContentType($contentType);
    }
}
