<?php

namespace Queue\Service\Queue\Manager\Bind;

use Queue\Service\Queue\Manager\Exchange;
use Queue\Service\Queue\Manager\Queue;

/**
 * Менеджер очередей / Интерфейс связи очереди с обменником
 *
 * Class BindInterface
 * @package Queue\Service\Queue\Manager\Bind
 */
interface BindInterface
{
    const ROUTING_KEY_PAID            = 'paid';
    const ROUTING_KEY_PAID_REJECT     = 'paidReject';
    const ROUTING_KEY_PAID_REJECTED   = 'paidRejected';
    const ROUTING_KEY_SEND            = 'send';
    const ROUTING_KEY_SEND_REJECT     = 'sendReject';
    const ROUTING_KEY_SEND_REJECTED   = 'sendRejected';
    const ROUTING_KEY_UPDATE          = 'update';
    const ROUTING_KEY_UPDATE_REJECT   = 'updateReject';
    const ROUTING_KEY_UPDATE_REJECTED = 'updateRejected';

    /**
     * Обменник
     *
     * @return Exchange\Configuration
     */
    public function getExchangeConfiguration();

    /**
     * Очередь
     *
     * @return Queue\Configuration
     */
    public function getQueueConfiguration();

    /**
     * Ключ для связи
     *
     * @return string
     */
    public function getRoutingKey();

    /**
     * Hash для проверки уникальности связи
     *
     * @return string
     */
    public function getHash();
}
