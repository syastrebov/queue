<?php

namespace Queue\Service\Queue\Manager\Queue;

use Queue\Entity\MessageInterface;

/**
 * Менеджер очередей / Интерфейс очереди
 *
 * Interface QueueInterface
 * @package Queue\Service\Queue\Manager\Queue
 */
interface QueueInterface
{
    const NAME_EVENT              = 'eventQueue';
    const NAME_PHANTOM            = 'phantomJsQueue';
    const NAME_NOTIFICATION       = 'notificationQueue';
    const NAME_SOCIAL_USER        = 'socialUserQueue';
    const NAME_LANDING            = 'landingQueue';
    const NAME_LANDING_REJECTED   = 'landingRejectedQueue';
    const NAME_ACQUIRING          = 'paymentAcquiringQueue';
    const NAME_ACQUIRING_REJECTED = 'paymentAcquiringRejectedQueue';
    const NAME_SEGMENT            = 'segmentQueue';
    const NAME_SEGMENT_REJECTED   = 'segmentRejectedQueue';

    /**
     * Название очереди
     *
     * @return string
     */
    public function getName();

    /**
     * Конфигурация очереди
     *
     * Конфигурацию нельзя изменять, если очередь уже инициализирована
     * Если нужна другая конфигурация, нужно создать еще одну очередь
     *
     * @return Configuration
     */
    public function getConfiguration();

    /**
     * Получить сообщение из очереди
     *
     * @return MessageInterface
     */
    public function get();

    /**
     * Получить сообщение из очереди с автоматическим удалением
     *
     * @return MessageInterface
     */
    public function shift();

    /**
     * Удалить сообдение из очереди
     *
     * @param  mixed $id
     * @return mixed
     */
    public function delete($id);

    /**
     * Вернуть сообщение в очередь
     *
     * @param  mixed $id
     * @return mixed
     */
    public function unlock($id);
}
