<?php

namespace Queue\Service\Queue\Manager\Adapter;

use Queue\Entity\MessageInterface;
use Queue\Service\Queue\Manager\Exchange;
use Queue\Service\Queue\Manager\Queue;

/**
 * Менеджер очередей / Интерфейс адаптера очередей
 *
 * Interface AdapterInterface
 * @package Queue\Service\Queue\Manager\Adapter
 */
interface AdapterInterface
{
    const TYPE_RABBIT = 'rabbit';
    const TYPE_MOCK   = 'mock';

    /**
     * Инициализация адаптера
     *
     * @return bool
     */
    public function init();

    /**
     * Связать очередь с обменником
     *
     * @param Exchange\Configuration $exchangeConfig
     * @param Queue\Configuration    $queueConfig
     * @param string                 $routingKey
     *
     * @return bool
     */
    public function bind(Exchange\Configuration $exchangeConfig, Queue\Configuration $queueConfig, $routingKey);

    /**
     * Отправить сообщение в очередь
     *
     * @param Exchange\Configuration $configuration
     * @param string                 $routingKey
     * @param string                 $message
     * @param string                 $contentType
     * @param int                    $attempt
     * @param int                    $priority
     *
     * @return bool
     */
    public function publish(
        Exchange\Configuration $configuration,
        $routingKey,
        $message,
        $contentType,
        $attempt,
        $priority
    );

    /**
     * Получить сообщение из очереди
     *
     * @param Queue\Configuration $configuration
     * @return MessageInterface|null
     */
    public function get(Queue\Configuration $configuration);

    /**
     * Удалить сообщение из очереди
     *
     * @param Queue\Configuration $configuration
     * @param mixed               $id
     *
     * @return bool
     */
    public function ack(Queue\Configuration $configuration, $id);

    /**
     * Положить сообщение назад в очередь
     *
     * @param Queue\Configuration $configuration
     * @param mixed               $id
     *
     * @return bool
     */
    public function nack(Queue\Configuration $configuration, $id);
}
